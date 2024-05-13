<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Family;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IWantFamilyVisa extends Command
{
    public static $command = 'iwantfamilyvisa';

    public static $title = [
        'ru' => 'Хочу получить визу по воссоединению с семьей или партнерское пребывание',
        'en' => 'I want to get a visa for reunion with my family or partner residence',
    ];

    public static $usage = ['iwantfamilyvisa'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'iwantfamilyvisa')],
            [array("👈 НАЗАД", Family::$command, '')],
        ], 'type');

        $text = implode("\n\n", [
            "Виза по воссоединению семьи или партнерству позволяет проживать с близким человеком, который имеет ВНЖ и ПМЖ.",
            "Она доступна для супругов, зарегистрированных партнеров, детей и в некоторых случаях других родственников.",
            "Процесс подачи проходит через консульство Чехии за границей или МВД внутри страны, если заявитель уже легально пребывает в Чехии.",
            "Рассмотрение заявки может занять несколько месяцев, после чего при одобрении виза дает право на проживание и в некоторых случаях трудоустройство без дополнительных разрешений.",
            "Этот статус может быть продлен и со временем привести к получению ПМЖ.",
            "*Нужна более подробная консультация?*\n*Ниже оставьте, пожалуйста, ваши контакты, чтобы менеджер с вами связался ☎️*",
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