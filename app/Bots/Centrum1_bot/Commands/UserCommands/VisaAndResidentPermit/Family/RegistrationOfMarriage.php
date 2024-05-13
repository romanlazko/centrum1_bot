<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Family;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class RegistrationOfMarriage extends Command
{
    public static $command = 'registrationofmarriage';

    public static $title = [
        'ru' => 'Оформление брака/партнерства в Чехии',
        'en' => 'Registration of Marriage/Partnership in Czech Republic',
    ];

    public static $usage = ['registrationofmarriage'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'registrationofmarriage')],
            [array("👈 НАЗАД", Family::$command, '')],
        ], 'type');

        $text = implode("\n\n", [
            "Оформление брака в Чехии проходит через местные органы записи актов гражданского состояния (ZÁPIS NA MATRICE) либо консульства.",
            "Пара должна подать заявление в выбранный ЗАГС, после чего назначается дата церемонии.",
            "Процедура для иностранцев действуют дополнительные требования. Брак может быть заключен в официальном учреждении или в другом месте по согласованию с властями.",
            "После регистрации супруги получают свидетельство о браке, которое при необходимости можно использовать для оформления визы или смены статуса пребывания.",
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