<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Student;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Nostrification extends Command
{
    public static $command = 'nostrification';

    public static $title = [
        'ru' => 'Нужно нострифицировать аттестат/диплом',
        'en' => 'Need to nostrify certificate/diploma',
    ];

    public static $usage = ['nostrification'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'nostrification')],
            [array("👈 НАЗАД", Student::$command, '')],
        ], 'type');

        $text = implode("\n", [
            "Нострификация школьного аттестата, диплома колледжа или университета в Чехии — это обязательный процесс признания вашего образования."."\n",
            "Для этого потребуется подготовить переводы документов, заверенные копии, заявление и, в некоторых случаях, пройти дополнительные экзамены."."\n",
            "Мы поможем разобраться в требованиях, собрать все необходимые документы и правильно подать заявку, чтобы вы смогли успешно продолжить обучение или начать работу в Чехии."."\n",
            "*Нужна более подробная консультация?*",
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