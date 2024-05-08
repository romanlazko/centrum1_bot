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
            ->query(CustomTelegramChat::with('tags:name')->orderByDesc('updated_at'))
            ->columns([
                ImageColumn::make('avatar')
                    ->defaultImageUrl(fn (CustomTelegramChat $telegram_chat) => (new Bot($this->telegram_bot->token))::getPhoto(['file_id' => $telegram_chat->photo]))
                    ->circular(),
                TextColumn::make('name')
                    ->searchable(['first_name', 'last_name'])
                    ->state(function (CustomTelegramChat $telegram_chat) {
                        return "$telegram_chat->first_name $telegram_chat->last_name";
                    })
                    ->description(fn (CustomTelegramChat $telegram_chat) => $telegram_chat->username)
                    ->url(fn (CustomTelegramChat $telegram_chat): string => route('chat.show', $telegram_chat)),
                TextColumn::make('latest_message')
                    ->state(fn (CustomTelegramChat $telegram_chat) => $telegram_chat->messages()->latest()->first()->text)
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('tags.name')
                    ->wrap(false)
                    ->badge(),
                TextColumn::make('updated_at')
                    ->sortable()
                    ->label('Last activity')
                    ->dateTime()
            ])
            ->actions([
                // EditAction::make()
                //     ->form([
                //         TagsInput::make('tags')
                //             ->afterStateHydrated(function (TagsInput $component, string $state) {
                //                 $component->state(json_decode($state));
                //             })
                //             ->dehydrateStateUsing(fn ($state) => json_encode($state))
                //             ->required(),
                //     ]),
            ])
            ->filters([
                SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->label('Tags')
                    ->options(fn () => Tag::all()->pluck('name', 'id'))
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
