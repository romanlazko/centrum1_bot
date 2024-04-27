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
use App\Models\Advertisement as ModelsAdvertisement;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Facades\Bus;
use Romanlazko\Telegram\Models\TelegramChat;

class Advertisement extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    #[Layout('layouts.app')]

    public function table(Table $table): Table
    {
        return $table
            ->query(ModelsAdvertisement::query())
            ->columns([
                ImageColumn::make('attachments'),
                TextColumn::make('name'),
                TextColumn::make('title')
                    ->description(fn (ModelsAdvertisement $advertisement) => $advertisement->description)
                    ->wrap(),
                ToggleColumn::make('is_active'),
            ])
            ->actions([
                DeleteAction::make(),
                EditAction::make()
                    ->form([
                        FileUpload::make('attachments')
                            ->multiple()
                            ->image(),
                        TextInput::make('name'),
                        TextInput::make('title'),
                        Textarea::make('description')
                    ])
                    ->slideOver()
                    ->closeModalByClickingAway(false),
                Action::make('create_distribution')
                    ->label('Create distribution')
                    ->action(function (ModelsAdvertisement $advertisement) {
                        $telegram_chats = TelegramChat::all();

                        foreach ($telegram_chats as $telegram_chat) {
                            $advertisement->logs()->create([
                                'telegram_chat_id' => $telegram_chat->id
                            ]);
                        }
                    })
                    ->visible(fn (ModelsAdvertisement $advertisement) => $advertisement->logs->isEmpty()),
                Action::make('show_distribution')
                    ->label('Show distribution')
                    ->url(fn (ModelsAdvertisement $advertisement) => route('advertisement.logs', $advertisement->id))
                    ->hidden(fn (ModelsAdvertisement $advertisement) => $advertisement->logs->isEmpty()),
            ])
            ->headerActions([
                CreateAction::make()
                    ->form([
                        FileUpload::make('attachments')
                            ->multiple()
                            ->image(),
                        TextInput::make('name'),
                        TextInput::make('title'),
                        Textarea::make('description')
                    ])
                    ->slideOver()
                    ->closeModalByClickingAway(false),
            ]);
    }
    
    public function render()
    {
        return view('livewire.telegram.advertisement');
    }
}
