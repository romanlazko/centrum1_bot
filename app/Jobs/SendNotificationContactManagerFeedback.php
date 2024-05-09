<?php

namespace App\Jobs;

use App\Bots\Centrum1_bot\Commands\UserCommands\AssignTag;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\BuyInsurance;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateInsuranceAgain;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateInsuranceNotifyLater;
use App\Bots\Centrum1_bot\Commands\UserCommands\ContactManager;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\Models\TelegramBot;
use Romanlazko\Telegram\Models\TelegramChat;

class SendNotificationContactManagerFeedback implements ShouldQueue, ShouldBeUnique
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public TelegramChat $telegram_chat;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $telegram_chat_id)
    {
        $this->telegram_chat = TelegramChat::find($telegram_chat_id);
    }
    /**
     * Handle the job.
     */
    public function handle(): void
    {
        $bot = new Bot(env('TELEGRAM_BOT_TOKEN', TelegramBot::first()->token));

        $buttons = BotApi::inlineKeyboard([
            [array('ДА ВСЕ ХОРОШО', AssignTag::$command, '#уже связались')],
            [array('НЕТ, СО МНОЙ НЕ СВЯЗАЛИСЬ', ContactManager::$command, '')],
            [array(BuyInsurance::getTitle('ru'), BuyInsurance::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ], 'temp');

        $text = implode("\n", [
            "*Всё ли прошло хорошо?☺*"."\n",
            "Здравствуйте!👋"."\n",
            "*Скажите, получили ли вы всю необходимую информацию и нужна ли вам еще какая либо помощь?*"
        ]);

        $bot::sendMessage([
            'text'                      => $text,
            'reply_markup'              => $buttons,
            'chat_id'                   => $this->telegram_chat->chat_id,
            'parse_mode'                => 'Markdown',
        ]);
    }

    public function uniqueId(): string
    {
        return $this->telegram_chat->chat_id;
    }

    public function middleware(): array
    {
        return [new WithoutOverlapping($this->telegram_chat->chat_id)];
    }
}
