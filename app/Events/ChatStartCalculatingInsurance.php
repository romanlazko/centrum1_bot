<?php

namespace App\Events;

use App\Models\Tag;
use App\Models\TelegramChatEvent;
use App\Models\TelegramChatTag;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Romanlazko\Telegram\Models\TelegramChat;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ChatStartCalculatingInsurance
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event_name = 'start_calculating_insurance';
    public $tag = '#пользователь начал подсчет страховки';

    /**
     * Create a new event instance.
     */
    public function __construct(public $telegram_chat_id)
    {
        TelegramChat::find($telegram_chat_id)->update([
            'event' => $this->event_name
        ]);

        Tag::firstOrCreate(['name' => $this->tag])
            ->chats()
            ->attach($this->telegram_chat_id);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
