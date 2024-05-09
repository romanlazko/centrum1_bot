<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Romanlazko\Telegram\Models\TelegramChat;

class Tag extends Model
{
    use HasFactory; use SoftDeletes;

    protected $guarded = [];

    public function chats()
    {
        return $this->belongsToMany(TelegramChat::class, 'telegram_chat_tags', 'tag_id', 'telegram_chat_id');
    }
}
