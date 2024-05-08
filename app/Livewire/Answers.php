<?php

namespace App\Livewire;

use App\Jobs\DispatchAdvertisementJob;
use App\Jobs\SendAdvertisementJob;
use App\Jobs\SendQuestionnaire;
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
use App\Models\Questionnaire\Answer;
use App\Models\Questionnaire\QuestionButton;
use App\Models\Questionnaire\Questionnaire;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Bus;
use Romanlazko\Telegram\Models\Advertisement;
use Romanlazko\Telegram\Models\TelegramChat;

class Answers extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    #[Layout('layouts.app')]

    public Questionnaire $questionnaire;
    private $questions;

    public function mount(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }

    public function table(Table $table): Table
    {
        $questions = $this->questionnaire->questions->map(function ($question) {
            return TextColumn::make('answer_'.$question->id)
                ->label($question->title)
                ->state(function (Answer $answer) use ($question) {
                    return QuestionButton::find($answer->answers[$question->id] ?? null)?->text;
                })
                ->sortable(query: function ( $query, string $direction) use ($question) {
                    return $query
                        ->orderBy('answers->'.$question->id, $direction);
                })
                ->badge();
        })
        ->toArray();

        $filters = $this->questionnaire->questions->map(function ($question) {
            return SelectFilter::make('answer_'.$question->id)
                ->label($question->title)
                ->options(fn () => $question->question_buttons->pluck('text', 'id'))
                ->query(fn ($query, array $data) => $query->when($data['value'], fn ($query) => $query->where('answers->'.$question->id, $data['value'])));         
        })
        ->toArray();
        
        return $table
            ->query($this->questionnaire->answers()->with('chat')->getQuery())
            ->columns([
                TextColumn::make('chat')
                    ->state(function (Answer $answer) {
                        return $answer->chat?->first_name ." ". $answer->chat?->last_name;
                    })
                    ->label('Пользователь')
                    ->description(fn (Answer $answer) => $answer->chat?->username),
                TextColumn::make('profile')
                    ->state(function (Answer $answer) {
                        return $answer->chat?->profile_first_name ." ". $answer->chat?->profile_last_name;
                    })
                    ->label('Профиль')
                    ->description(fn (Answer $answer) => $answer->chat?->profile_phone),
                ...$questions,
                TextColumn::make('status')
                    ->badge()
                    ->color(function (Answer $answer) {
                        return match ($answer->status) {
                            'created' => 'primary',
                            'in_progress' => 'warning',
                            'success' => 'success',
                            'failed' => 'danger',
                            'canceled' => 'danger',
                            default => 'secondary',
                        };
                    })
                    ->sortable(),
                TextColumn::make('message'),
                TextColumn::make('created_at')
                    ->label('Дата создания/обновления')
                    ->wrapHeader()
                    ->state(fn (Answer $answer) => $answer->created_at->diffForHumans())
                    ->description(fn (Answer $answer) => $answer->updated_at->diffForHumans())
                    ->sortable(),
                
            ])
            ->actions([
                DeleteAction::make(),
            ])
            ->filters([
                ...$filters
            ]);
    }

    public function render()
    {
        return view('livewire.answers');
    }
}
