<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Romanlazko\Telegram\Models\TelegramChat;
use Romanlazko\Telegram\Models\TelegramMessage;

class CustomTelegramChat extends TelegramChat
{
    protected $table = 'telegram_chats';
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'telegram_chat_tags', 'telegram_chat_id', 'tag_id');
    }
}
