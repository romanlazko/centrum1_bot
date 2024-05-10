<?php

namespace App\Listeners;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateInsurance;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateInsuranceNotifyLater;
use App\Bots\Centrum1_bot\Commands\UserCommands\ContactManager;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Events\ChatStartCalculatingInsurance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\App;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\Models\TelegramBot;
use Romanlazko\Telegram\Models\TelegramChat;

class SendNotificationToFinishCalculatingInsurance implements ShouldQueue, ShouldBeUnique
{
    /**
     * Handle the event.
     */
    public function handle(ChatStartCalculatingInsurance $event): void
    {
        $telegram_chat = TelegramChat::find($event->telegram_chat_id);

        if ($telegram_chat->event != $event->event_name) {
            return;
        }

        $telegram_chat->update([
            'event' => null
        ]);

        $bot = new Bot(env('TELEGRAM_BOT_TOKEN', TelegramBot::first()->token));

        $buttons = BotApi::inlineKeyboard([
            [array(CalculateInsurance::getTitle('ru'), CalculateInsurance::$command, '')],
            [array(CalculateInsuranceNotifyLater::getTitle('ru'), CalculateInsuranceNotifyLater::$command, '')],
            [array(ContactManager::getTitle('ru'), ContactManager::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "Мы видим, что вы не закончили расчёт стоимости страховки, возможно вас отвлекли или у вас возникли трудности👌"."\n",

            "Наш бот настроен так, чтобы подобрать для вас лучшее предложение на рынке и сделать оптимальный выбор."."\n",

            "*Хотели бы вы продолжить расчёт?*"
        ]);

        $bot::sendMessage([
            'text'                      => $text,
            'reply_markup'              => $buttons,
            'chat_id'                   => $telegram_chat->chat_id,
            'parse_mode'                => 'Markdown',
        ]);
    }

    public function withDelay(): int
    {
        if (App::environment('production')) {
            $currentTime = now();

            // Добавляем 2 часа к текущему времени
            $sendTime = $currentTime->copy()->addHours(2);

            // Если время отправки больше 20:00, устанавливаем его на следующее утро в 9:00
            if ($sendTime->hour >= 20) {
                $sendTime->addDay()->hour(9)->minute(0)->second(0);
            }

            // Вычисляем разницу в секундах между текущим временем и временем отправки
            $secondsUntilSend = $currentTime->diffInSeconds($sendTime);

            return $secondsUntilSend;
        }
        return 1200;
    }
}
