<?php

namespace App\Events;

use App\Models\TelegramChatEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Romanlazko\Telegram\Models\TelegramChat;

class ChatFinishCalculatingInsurance
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event_name = 'finish_calculating_insurance';

    /**
     * Create a new event instance.
     */
    public function __construct(public $telegram_chat_id)
    {
        TelegramChat::find($telegram_chat_id)->update([
            'event' => $this->event_name
        ]);
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
