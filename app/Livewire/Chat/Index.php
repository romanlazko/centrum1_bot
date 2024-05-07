<?php

namespace App\Livewire\Chat;

use App\Jobs\SendAdvertisementJob;
use App\Jobs\SendQuestionnaire;
use App\Models\Advertisement;
use App\Models\Questionnaire\QuestionButton;
use App\Models\Questionnaire\Questionnaire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Google\Service\Compute\Tags;
use Livewire\Component;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\Models\TelegramChat;

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
            ->query($this->telegram_bot->chats()->with('messages', function ($query) {
                return $query->latest()->limit(1);
            })->getQuery()->orderBy('updated_at'))
            ->columns([
                ImageColumn::make('avatar')
                    ->defaultImageUrl(fn (TelegramChat $telegram_chat) => (new Bot($this->telegram_bot->token))::getPhoto(['file_id' => $telegram_chat->photo]))
                    ->circular(),
                TextColumn::make('name')
                    ->searchable(['first_name', 'last_name'])
                    ->state(function (TelegramChat $telegram_chat) {
                        return "$telegram_chat->first_name $telegram_chat->last_name";
                    })
                    ->description(fn (TelegramChat $telegram_chat) => $telegram_chat->username)
                    ->url(fn (TelegramChat $telegram_chat): string => route('chat.show', $telegram_chat)),
                SelectColumn::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'user' => 'User',
                    ]),
                TextColumn::make('messages.text')->limit(50),
                TextColumn::make('tags')
                    ->state(fn (TelegramChat $telegram_chat) => json_decode($telegram_chat->tags))
                    ->badge(),
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TagsInput::make('tags')
                            ->afterStateHydrated(function (TagsInput $component, string $state) {
                                $component->state(json_decode($state));
                            })
                            ->dehydrateStateUsing(fn ($state) => json_encode($state))
                            ->required(),
                    ]),
            ])
            ->filters([
                SelectFilter::make('tags')
                    ->label('Tags')
                    ->options(fn () => QuestionButton::all()->pluck('value', 'value'))
                    ->query(fn ($query, array $data) => $query->when($data['value'] ?? null, fn ($query) => $query->whereJsonContains('tags', $data['value'])))
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
                            SendQuestionnaire::dispatch($data['questionnaire'], $telegram_chat->chat_id);
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
            ])
            ->deferLoading();
    }
    public function render()
    {
        return view('livewire.telegram.chat.index');
    }
}
