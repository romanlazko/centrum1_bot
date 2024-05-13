<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class MyApplicationTakingLongTimeToProcess extends Command
{
    public static $command = 'matlttp';

    public static $title = [
        'ru' => '🕐 ДОЛГО РАССМАТРИВАЮТ ЗАЯВЛЕНИЕ',
        'en' => '🕐 MY APPLICATION TAKING LONG TIME TO PROCESS',
    ];

    public static $usage = ['matlttp'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'matlttp')],
            [array("👈 НАЗАД", MenuCommand::$command, '')],
        ], 'type');

        $text = implode("\n", [
            "Сроки рассмотрения заявления зависят от цели пребывания и начинает бежать с момента донесения полного пакета документов:"."\n",
            "• ВНЖ (учеба, исследования, семья исследователя) – до 60 дней",
            "• Рабочая карта – до 90 дней",
            "• ВНЖ (совместное проживание семьи) – до 270 дней",
            "• Синяя карта – 90 дней (30–60 дней при наличии карты другого государства ЕС)",
            "• ВНЖ (семья обладателя синей карты/инвестора) – до 90 дней",
            "• Гражданство – 180 дней"."\n",

            "Если срок рассмотрения вышел, то рекомендуем подать запрос об ускорении и жалобу за нарушение сроков"."\n",

            "*Нужна более подробная информация?*",
            "*Ниже оставьте, пожалуйста, ваши контакты, чтобы менеджер с вами связался ☎️*",
        ]);

        $data = [
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        return BotApi::returnInline($data);
    }
}