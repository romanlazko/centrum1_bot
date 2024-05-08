<?php

namespace App\Jobs;

use App\Models\Advertisement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\Models\TelegramBot;

class SendAdvertisementJob implements ShouldQueue
{use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Advertisement $advertisement;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $advertisement_id, protected $chat_id)
    {
        $this->advertisement = Advertisement::find($advertisement_id);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $bot = new Bot(env('TELEGRAM_BOT_TOKEN', TelegramBot::first()->token));

        $text = implode("\n", [
            "*{$this->advertisement->title}*"."\n",
            $this->advertisement->description,
        ]);

        $images = [];

        if (is_array($this->advertisement->attachments)) {
            foreach ($this->advertisement->attachments ?? [] as $attachment) {
                $images[] = env('APP_URL')."/storage/".$attachment;
            }
        }
        else {
            $images[] = env('APP_URL')."/storage/".$this->advertisement->attachments;
        }

        $bot::sendMessageWithMedia([
            'text'                      => $text,
            'chat_id'                   => $this->chat_id,
            'media'                     => $images ?? null,
            'parse_mode'                => 'Markdown',
        ]);


        sleep(1);
    }

    public function uniqueId(): string
    {
        return $this->advertisement_id . '_' . $this->chat_id;
    }
}
