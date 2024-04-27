<?php

namespace App\Jobs;

use App\Models\Advertisement;
use App\Models\AdvertisementLog;
use App\Models\MessageResult;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\Models\TelegramBot;
use Romanlazko\Telegram\Models\TelegramChat;
use Throwable;

class DispatchAdvertisementJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $advertisement_log;
    /**
     * Create a new job instance.
     */
    public function __construct($advertisement_log_id)
    {
        $this->advertisement_log = AdvertisementLog::find($advertisement_log_id);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->advertisement_log->advertisement->is_active) {

            $bot = new Bot(env('TELEGRAM_BOT_TOKEN', TelegramBot::first()->token));

            $text = implode("\n", [
                "*{$this->advertisement_log->advertisement->title}*"."\n",
                $this->advertisement_log->advertisement->description,
            ]);

            $images = [];

            if (is_array($this->advertisement_log->advertisement->attachments)) {
                foreach ($this->advertisement_log->advertisement->attachments ?? [] as $attachment) {
                    $images[] = env('APP_URL')."/storage/".$attachment;
                }
            }
            else {
                $images[] = env('APP_URL')."/storage/".$this->advertisement_log->advertisement->attachments;
            }
    
            $bot::sendMessageWithMedia([
                'text'                      => $text,
                'chat_id'                   => $this->advertisement_log->chat->chat_id,
                'media'                     => $images ?? null,
                'parse_mode'                => 'Markdown',
            ]);

            $this->advertisement_log->update([
                'message'   => null,
                'status' => 'success'
            ]);

            sleep(1);
        }
    }

    public function failed(Throwable $exception): void
    {
        $this->advertisement_log->update([
            'message'           => $exception->getMessage(),
            'status'            => 'failed',
            'exception'         => $exception->getTraceAsString(),
        ]);
    }
}
