<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Romanlazko\Telegram\Models\TelegramChat;

class Profile extends Model
{
    use HasFactory; use SoftDeletes;

    protected $guarded = [];

    public function chat()
    {
        return $this->belongsTo(TelegramChat::class);
    }

    public function questionnaires()
    {
        return $this->hasMany(ProfileQuestionnaire::class);
    }

    public function scopeFindByChatId($query, $telegram_chat_id)
    {
        return $query->where('telegram_chat_id', $telegram_chat_id)->first();
    }
}
