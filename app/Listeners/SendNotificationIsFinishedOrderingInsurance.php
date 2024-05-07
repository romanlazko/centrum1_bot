<?php

namespace App\Listeners;

use App\Bots\Centrum1_bot\Commands\UserCommands\AssignTag;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\BuyInsurance;
use App\Bots\Centrum1_bot\Commands\UserCommands\ContactManager;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Events\ChatStartOrderingInsurance;
use App\Models\TelegramChatEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\Models\TelegramBot;
use Romanlazko\Telegram\Models\TelegramChat;

class SendNotificationIsFinishedOrderingInsurance implements ShouldQueue
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
    public function handle(ChatStartOrderingInsurance $event): void
    {
        $telegram_chat = TelegramChat::find($event->telegram_chat_id);

        $telegram_chat_event = TelegramChatEvent::where('telegram_chat_id', $event->telegram_chat_id)->latest()->first()->event;

        if ($telegram_chat_event != $event->event_name) {
            return;
        }

        $bot = new Bot(env('TELEGRAM_BOT_TOKEN', TelegramBot::first()->token));

        $buttons = BotApi::inlineKeyboard([
            [array('Да все хорошо', AssignTag::$command, '#проверить оформил ли')],
            [array('Я уже жду страховку', AssignTag::$command, '#отправить страховку')],
            [array(ContactManager::getTitle('ru'), ContactManager::$command, '')],
            [array(BuyInsurance::getTitle('ru'), BuyInsurance::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ], 'temp');

        $text = implode("\n", [
            "*Всё ли прошло хорошо?☺*"."\n",
            "Здравствуйте!👋"."\n",
            "Мы рады, что вы воспользовались нашим ботом, для расчёта страховки!",
            "Для нас очень важен ваш комфорт и мы всегда рады обратной связи."."\n",
            "*Скажите, получили ли вы всю необходимую информацию и нужна ли вам еще какая либо помощь?*"
        ]);

        $bot::sendMessage([
            'text'                      => $text,
            'reply_markup'              => $buttons,
            'chat_id'                   => $telegram_chat->chat_id,
            'parse_mode'                => 'Markdown',
        ]);
    }

    public function uniqueId(ChatStartOrderingInsurance $event): string
    {
        return $event->telegram_chat_id;
    }
}
