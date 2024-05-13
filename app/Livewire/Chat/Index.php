<?php

namespace App\Livewire\Chat;

use App\Jobs\SendAdvertisementJob;
use App\Jobs\SendQuestionnaire;
use App\Models\Advertisement;
use App\Models\CustomTelegramChat;
use App\Models\Questionnaire\QuestionButton;
use App\Models\Questionnaire\Questionnaire;
use App\Models\Tag;
use App\Models\TelegramChatTag;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Google\Service\Compute\Tags;
use Livewire\Component;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\Models\TelegramChat;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Collection;

class Index extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $telegram_bot;

    public function mount()
    {
        $this->telegram_bot = auth()?->user()?->telegram_bot;
    }
    
    public function table(Table $table): Table
    {
        // $filters = $this->questionnaire->questions->map(function ($question) {
        //     return SelectFilter::make('')
        //         ->label($question->title)
        //         ->options(fn () => $question->question_buttons->pluck('text', 'id'))
        //         ->query(fn ($query, array $data) => $query->when($data['value'], fn ($query) => $query->where('answers->'.$question->id, $data['value'])));         
        // })
        // ->toArray();

        return $table
            ->query(CustomTelegramChat::with('tags:name'))
            ->columns([
                // ImageColumn::make('avatar')
                //     ->defaultImageUrl(fn (CustomTelegramChat $telegram_chat) => (new Bot($this->telegram_bot->token))::getPhoto(['file_id' => $telegram_chat->photo]))
                //     ->circular(),
                TextColumn::make('name')
                    ->label('Чат')
                    ->searchable(['first_name', 'last_name', 'username', 'profile_first_name', 'profile_last_name', 'profile_phone'])
                    ->state(function (CustomTelegramChat $telegram_chat) {
                        return "$telegram_chat->first_name $telegram_chat->last_name";
                    })
                    ->description(fn (CustomTelegramChat $telegram_chat) => $telegram_chat->username)
                    ->url(fn (CustomTelegramChat $telegram_chat): string => route('chat.show', $telegram_chat)),
                TextColumn::make('profile')
                    ->label('Профиль')
                    ->state(function (CustomTelegramChat $telegram_chat) {
                        return "$telegram_chat->profile_first_name $telegram_chat->profile_last_name";
                    })
                    ->description(fn (CustomTelegramChat $telegram_chat) => $telegram_chat->profile_phone),
                TextColumn::make('latest_message')
                    ->label('Последнее сообщение')
                    ->state(fn (CustomTelegramChat $telegram_chat) => $telegram_chat->messages()->latest()->first()->text)
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('tags')
                    ->label('Теги')
                    ->state(fn (CustomTelegramChat $telegram_chat) => $telegram_chat->tags()->orderByDesc('telegram_chat_tags.id')->limit(10)->pluck('name'))
                    ->wrap(false)
                    ->badge(),
                TextInputColumn::make('communication_date')
                    ->wrapHeader()
                    ->label('Дата следующей связи')
                    ->type('date')
                    ->sortable(),
                TextInputColumn::make('issue_date')
                    ->label('Предпологаемая дата оформления')
                    ->wrapHeader()
                    ->type('date')
                    ->sortable(),
                TextInputColumn::make('notes')
                    ->label('Заметки')
                    ->type('text')
                    ->extraAttributes(['class' => 'w-max']),
                ToggleColumn::make('is_communicated')
                    ->label('Связан')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->sortable()
                    ->label('Последняя активность')
                    ->dateTime()
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        Select::make('tags')
                            ->relationship('tags', 'id')
                            ->label('Теги')
                            ->multiple()
                            ->options(Tag::all()->pluck('name', 'id'))
                            ->createOptionForm([
                                TextInput::make('name')
                            ])
                            ->createOptionUsing(fn (array $data) => Tag::create($data)->id),
                    ])
                    ->slideOver(),
            ])
            ->filters([
                SelectFilter::make('Включить')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Включить')
                    ->options(fn () => Tag::all()->pluck('name', 'id')),
                SelectFilter::make('Исключить')
                    ->options(fn () => Tag::all()->pluck('name', 'id'))
                    ->label('Исключить')
                    ->multiple()
                    ->query(function ($query, array $data) {
                        $query->whereDoesntHave('tags', fn ($query) => 
                            $query->whereIn('tags.id', $data['values'] ?? [])
                        );  
                    })
            ])
            ->bulkActions([
                BulkAction::make('send_questionnaire')
                    ->label('Отправить опрос')
                    ->form([
                        Select::make('questionnaire')
                            ->options(fn () => Questionnaire::all()->pluck('name', 'id'))
                    ])
                    ->slideOver()
                    ->action(function (Collection $telegram_chats, array $data) {
                        $telegram_chats->each(function ($telegram_chat) use ($data) {
                            // dump($telegram_chat);
                            SendQuestionnaire::dispatch($data['questionnaire'], $telegram_chat->id);
                        });
                    }),
                BulkAction::make('send_advertisement')
                    ->label('Отправить рекламу')
                    ->form([
                        Select::make('advertisement')
                            ->options(fn () => Advertisement::all()->pluck('name', 'id'))
                    ])
                    ->slideOver()
                    ->action(function (Collection $telegram_chats, array $data) {
                        $telegram_chats->each(function ($telegram_chat) use ($data) {
                            SendAdvertisementJob::dispatch($data['advertisement'], $telegram_chat->chat_id);
                        });
                    })
            ]);
    }
    public function render()
    {
        return view('livewire.telegram.chat.index');
    }
}
