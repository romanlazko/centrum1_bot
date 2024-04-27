<?php

namespace App\Livewire\Telegram;


use App\Jobs\DispatchAdvertisementJob;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Livewire\Attributes\Layout;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Romanlazko\Telegram\App\Bot;
use App\Models\Advertisement;
use App\Models\AdvertisementLog;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Facades\Bus;
use Romanlazko\Telegram\Models\TelegramChat;

class AdvertisementLogs extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    #[Layout('layouts.app')]

    public Advertisement $advertisement;

    public function mount(Advertisement $advertisement)
    {
        $this->advertisement = $advertisement;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->advertisement->logs()->getQuery())
            ->columns([
                TextColumn::make('chat')
                    ->state(function (AdvertisementLog $advertisement_log) {
                        return $advertisement_log->chat?->first_name ." ". $advertisement_log->chat?->last_name;
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(function (AdvertisementLog $advertisement_log) {
                        return match ($advertisement_log->status) {
                            'in_progress' => 'warning',
                            'success' => 'success',
                            'failed' => 'danger',
                            default => 'secondary',
                        };
                    })
                    ->sortable(),
                TextColumn::make('message'),

            ])
            ->actions([
                Action::make('send')
                    ->action(function (AdvertisementLog $advertisement_log) {
                        $advertisement_log->update([
                            'status' => 'in_progress'
                        ]);
                        dispatch(new DispatchAdvertisementJob($advertisement_log->id));
                    })
                    ->hidden(fn (AdvertisementLog $advertisement_log) => $advertisement_log->status == 'in_progress'),
            ])
            ->headerActions([
                Action::make('Send everyone')
                    ->action(function () {
                        $this->advertisement->logs;

                        $jobs = [];

                        foreach ($this->advertisement->logs as $log) {
                            $log->update([
                                'status' => 'in_progress'
                            ]);
                            $jobs[] = new DispatchAdvertisementJob($log->id);
                        }

                        $this->advertisement->update([
                            'batch_id' => Bus::batch($jobs)->dispatch()->id,
                        ]);
                    })
                    ->hidden(function () {
                        $batch = Bus::findBatch($this->advertisement->batch_id ?? '');

                        return $batch?->pendingJobs > 0;
                    })
            ])
            ->heading(function () {
                $in_progress = $this->advertisement->logs()->where('status', 'in_progress')->count();
                $success = $this->advertisement->logs()->where('status', 'success')->count();
                $failed = $this->advertisement->logs()->where('status', 'failed')->count();

                return $this->advertisement->title . ": " . $in_progress . " in progress, " . $success . " success, " . $failed . " failed";
            });
    }

    public function render()
    {
        return view('livewire.telegram.advertisement-logs');
    }
}
