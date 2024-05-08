<?php

namespace App\Events;

use App\Models\Tag;
use App\Models\TelegramChatEvent;
use App\Models\TelegramChatTag;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Romanlazko\Telegram\Models\TelegramChat;

class ChatWantsToContactManager
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event_name = 'start_calculating_insurance';
    public $tag = '#хочет связаться с менеджером';

    /**
     * Create a new event instance.
     */
    public function __construct(public $telegram_chat_id)
    {
        
        // $this->assignEvent();
        $this->assignTag();
    }

    private function assignEvent()
    {
        TelegramChatEvent::create([
            'telegram_chat_id' => $this->telegram_chat_id,
            'event' => $this->event_name,
        ]);
    }

    private function assignTag()
    {
        $telegram_chat = TelegramChat::find($this->telegram_chat_id);

        $tag = Tag::where('name', $this->tag)->first();

        if (!$tag) {
            $tag = Tag::create([
                'name' => $this->tag
            ]);
        }

        TelegramChatTag::create([
            'telegram_chat_id' => $telegram_chat->id,
            'tag_id' => $tag->id,
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
