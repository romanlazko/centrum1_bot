<?php

namespace App\Livewire\Chat;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\Models\TelegramChat;

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
                TextColumn::make('role')
                    ->badge(),
                TextColumn::make('messages.text')->limit(50)
            ])
            ->actions([
                DeleteAction::make(),
            ]);
    }
    public function render()
    {
        return view('livewire.telegram.chat.index');
    }
}
