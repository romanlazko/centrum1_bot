<?php

namespace App\Listeners;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\BuyInsurance;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateInsuranceAgain;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\OrderInsuranceNotifyLater;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Events\ChatFinishCalculatingInsurance;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\TelegramChatEvent;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\Models\TelegramBot;
use Romanlazko\Telegram\Models\TelegramChat;

class SendNotificationToFinishOrderingInsurance implements ShouldQueue
{

    public $delay = 60;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ChatFinishCalculatingInsurance $event): void
    {
        $telegram_chat = TelegramChat::find($event->telegram_chat_id);

        $telegram_chat_event = TelegramChatEvent::where('telegram_chat_id', $event->telegram_chat_id)->latest()->first()->event;

        if ($telegram_chat_event != $event->event_name) {
            return;
        }

        $bot = new Bot(env('TELEGRAM_BOT_TOKEN', TelegramBot::first()->token));

        $buttons = BotApi::inlineKeyboard([
            [array(BuyInsurance::getTitle('ru'), BuyInsurance::$command, '')],
            [array(CalculateInsuranceAgain::getTitle('ru'), CalculateInsuranceAgain::$command, '')],
            [array(OrderInsuranceNotifyLater::getTitle('ru'), OrderInsuranceNotifyLater::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "*Вы не закончили оформление страховки*"."\n",
            "Выберите из предложенных вариантов."
        ]);

        $bot::sendMessage([
            'text'                      => $text,
            'reply_markup'              => $buttons,
            'chat_id'                   => $telegram_chat->chat_id,
            'parse_mode'                => 'Markdown',
        ]);
    }

    public function uniqueId(ChatFinishCalculatingInsurance $event): string
    {
        return $event->telegram_chat_id;
    }
}
