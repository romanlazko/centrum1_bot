<?php

namespace App\Livewire;

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
use App\Models\Questionnaire\Questionnaire;
use App\Models\Tag;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Facades\Bus;
use Romanlazko\Telegram\Models\TelegramChat;

class Questionnaires extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    #[Layout('layouts.app')]

    public function table(Table $table): Table
    {
        return $table
            ->query(Questionnaire::query())
            ->columns([
                TextColumn::make('name')
                    ->url(fn (Questionnaire $questionnaire) => route('questionnaire.show', $questionnaire->id)),
                TextColumn::make('answers_count')
                    ->state(fn (Questionnaire $questionnaire) => $questionnaire->answers()->count()),
                ToggleColumn::make('is_active'),
                TextColumn::make('created_at'),
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('name'),
                        Repeater::make('questions')
                            ->relationship('questions')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Заголовок')
                                    ->required(),
                                Textarea::make('body')
                                    ->label('Текст')
                                    ->helperText('Текст нужно писать в формате Markdown: жирный - * *, курсив - _ _. Ссылки должны быть в виде *[text](url)*. Логины в телеграм должны быть в виде *@login*.')
                                    ->autosize()
                                    ->required(),
                                Repeater::make('buttons')
                                    ->relationship('question_buttons')
                                    ->schema([
                                        Select::make('type')
                                            ->options([
                                                'button' => 'Кнопка',
                                            ]),
                                        TextInput::make('text')
                                            ->label('Текст кнопки'),
                                        Select::make('tag_id')
                                            ->label('Тэг')
                                            ->options(Tag::all()->pluck('name', 'id'))
                                            ->createOptionForm([
                                                TextInput::make('name')
                                            ])
                                            ->createOptionUsing(fn (array $data) => Tag::create($data)->id)
                                            ->helperText('Тэг будет присвоен пользователю при нажатии'),
                                    ])
                                    ->columns(3)
                            ]),
                    ])
                    ->slideOver()
                    ->closeModalByClickingAway(false),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->form([
                        TextInput::make('name'),
                        Repeater::make('questions')
                            ->relationship('questions')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Заголовок')
                                    ->required(),
                                Textarea::make('body')
                                    ->label('Текст')
                                    ->helperText('Текст нужно писать в формате Markdown: жирный - * *, курсив - _ _. Ссылки должны быть в виде *[text](url)*. Логины в телеграм должны быть в виде *@login*.')
                                    ->autosize()
                                    ->required(),
                                Repeater::make('buttons')
                                    ->relationship('question_buttons')
                                    ->schema([
                                        Select::make('type')
                                            ->options([
                                                'button' => 'Кнопка',
                                            ]),
                                        TextInput::make('text')
                                            ->label('Текст кнопки'),
                                        Select::make('tag_id')
                                            ->label('Тэг')
                                            ->options(Tag::all()->pluck('name', 'id'))
                                            ->helperText('Тэг будет присвоен пользователю при нажатии'),
                                    ])
                                    ->columns(3)
                            ]),
                    ])
                    ->slideOver()
                    ->closeModalByClickingAway(false),
            ]);
    }

    public function render()
    {
        return view('livewire.questionnaires');
    }
}
