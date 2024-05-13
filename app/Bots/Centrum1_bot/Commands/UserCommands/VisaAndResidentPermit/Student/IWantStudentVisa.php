<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Student;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IWantStudentVisa extends Command
{
    public static $command = 'iwantstudentvisa';

    public static $title = [
        'ru' => 'Хочу получить студенческую визу',
        'en' => 'I want to get a student visa',
    ];

    public static $usage = ['iwantstudentvisa'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'iwantstudentvisa')],
            [array("👈 НАЗАД", Student::$command, '')],
        ], 'type');

        $text = implode("\n", [
            "Чтобы получить студенческую визу в Чехию, нужно поступить в аккредитованное учебное заведение и получить подтверждение об обучении."."\n",
            "Затем собрать пакет документов и податься на рассмотрение. Рассмотрение заявки занимает до 60 дней."."\n",
            "*Нужна более подробная информация?*",
            "*Ниже оставьте пожалуйста ваши контакты, чтобы менеджер с вами связался ☎️*",
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