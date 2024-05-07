<?php

namespace App\Listeners;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateInsurance;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateInsuranceAgain;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateInsuranceNotifyLater;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Events\ChatStartCalculatingInsurance;
use App\Models\TelegramChatEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\Models\TelegramBot;
use Romanlazko\Telegram\Models\TelegramChat;

class SendNotificationToFinishCalculatingInsurance implements ShouldQueue
{

    public $delay = 60;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(ChatStartCalculatingInsurance $event): void
    {
        $telegram_chat = TelegramChat::find($event->telegram_chat_id);

        $telegram_chat_event = TelegramChatEvent::where('telegram_chat_id', $event->telegram_chat_id)->latest()->first()->event;

        if ($telegram_chat_event != $event->event_name) {
            return;
        }

        $bot = new Bot(env('TELEGRAM_BOT_TOKEN', TelegramBot::first()->token));

        $buttons = BotApi::inlineKeyboard([
            [array(CalculateInsuranceAgain::getTitle('ru'), CalculateInsuranceAgain::$command, '')],
            [array(CalculateInsuranceNotifyLater::getTitle('ru'), CalculateInsuranceNotifyLater::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "*Вы не закончили подсчет стоимости страховки*"."\n",
            "Выберите из предложенных вариантов."
        ]);

        $bot::sendMessage([
            'text'                      => $text,
            'reply_markup'              => $buttons,
            'chat_id'                   => $telegram_chat->chat_id,
            'parse_mode'                => 'Markdown',
        ]);
    }

    public function uniqueId(ChatStartCalculatingInsurance $event): string
    {
        return $event->telegram_chat_id;
    }


}
