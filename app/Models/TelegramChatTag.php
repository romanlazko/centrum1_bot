<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Romanlazko\Telegram\Models\TelegramChat;

class TelegramChatTag extends Model
{
    use HasFactory; use SoftDeletes;

    protected $guarded = [];

    public function chat()
    {
        return $this->belongsTo(TelegramChat::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function getTagNameAttribute()
    {
        return $this->tag->name;
    }
}
