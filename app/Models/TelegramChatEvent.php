<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Romanlazko\Telegram\Models\TelegramChat;

class TelegramChatEvent extends Model
{
    use HasFactory; use SoftDeletes;

    protected $guarded = [];

    public function chat()
    {
        return $this->belongsTo(TelegramChat::class);
    }
}
