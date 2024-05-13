<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Student;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IWantChangePurposeOfStaying extends Command
{
    public static $command = 'iwantchangepurposeofstaying';

    public static $title = [
        'ru' => 'Хочу поменять цель пребывания',
        'en' => 'I want to change the purpose of staying',
    ];

    public static $usage = ['iwantchangepurposeofstaying'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'iwantchangepurposeofstaying')],
            [array("👈 НАЗАД", Student::$command, '')],
        ], 'type');

        $text = implode("\n", [
            "Смена цели пребывания в Чехии — это процесс изменения основания для долгосрочного пребывания, например, с учебной визы на рабочую или предпринимательскую."."\n",
            "Для этого необходимо подать заявление в МВД Чехии до истечения текущего разрешения, предоставив документы, подтверждающие новую цель."."\n",
            "Важно заранее изучить все правила, так как некорректное оформление или пропуск сроков могут привести к отказу или необходимости покинуть страну."."\n",
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