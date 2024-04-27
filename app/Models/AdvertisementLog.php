<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Romanlazko\Telegram\Models\TelegramChat;

class AdvertisementLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function chat()
    {
        return $this->belongsTo(TelegramChat::class, 'telegram_chat_id');
    }

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'advertisement_id');
    }
}
