<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\LanguageCourses;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class PrivatUniversity extends Command
{
    public static $command = 'privatuniversity';

    public static $title = [
        'ru' => 'Поступление в частный ВУЗ ',
        'en' => 'Entrance to a private university',
    ];

    public static $usage = ['privatuniversity'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'privatuniversity')],
            [array("👈 НАЗАД", LanguageCourses::$command, '')],
        ], 'type');

        $text = implode("\n", [
            "Частный экономический ВУЗ в Брно и Праге"."\n",
            "- виза 23,24 (studium)",
            "- свободный доступ на рынок труда",
            "- обязательное наличие гос нострификации",
            "- подтверждение об обучении на академический год",
            "- рассрочка за обучение"."\n",
            "Специальности:",
            "- globální podnikání a management ",
            "- management lidských zdrojů",
            "- marketing"."\n",
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