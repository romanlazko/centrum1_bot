<?php

namespace App\Listeners;

use App\Bots\Centrum1_bot\Commands\UserCommands\AssignTag;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\BuyInsurance;
use App\Bots\Centrum1_bot\Commands\UserCommands\ContactManager;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Events\ChatWantsToContactManager;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\Models\TelegramBot;
use Romanlazko\Telegram\Models\TelegramChat;

use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotificationContactManagerFeedback implements ShouldQueue
{
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
    public function handle(ChatWantsToContactManager $event): void
    {
        $telegram_chat = TelegramChat::find($event->telegram_chat_id);

        $bot = new Bot(env('TELEGRAM_BOT_TOKEN', TelegramBot::first()->token));

        $buttons = BotApi::inlineKeyboard([
            [array('Да все хорошо', AssignTag::$command, '#уже связались')],
            [array('Нет, со мной не связались', ContactManager::$command, '')],
            [array(BuyInsurance::getTitle('ru'), BuyInsurance::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ], 'temp');

        $text = implode("\n", [
            "*Всё ли прошло хорошо?☺*"."\n",
            "Здравствуйте!👋"."\n",
            "Связался ли с вами менеджер, все ли прошло хорошо? Можем ли мы вам еще чем то помочь?"
        ]);

        $bot::sendMessage([
            'text'                      => $text,
            'reply_markup'              => $buttons,
            'chat_id'                   => $telegram_chat->chat_id,
            'parse_mode'                => 'Markdown',
        ]);
    }

    public function uniqueId(ChatWantsToContactManager $event): string
    {
        return $event->telegram_chat_id;
    }

    public function withDelay(): int
    {
        // $currentTime = now();

        // // Добавляем 2 часа к текущему времени
        // $sendTime = $currentTime->copy()->addHours(2);

        // // Если время отправки больше 20:00, устанавливаем его на следующее утро в 9:00
        // if ($sendTime->hour >= 20) {
        //     $sendTime->addDay()->hour(9)->minute(0)->second(0);
        // }

        // // Вычисляем разницу в секундах между текущим временем и временем отправки
        // $secondsUntilSend = $currentTime->diffInSeconds($sendTime);

        // return $secondsUntilSend;
        return 60;
    }
}
