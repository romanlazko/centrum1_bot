<?php

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\AfterUniversityVisa\AfterUniversityVisa;
use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Business\Business;
use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Family\Family;
use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\LanguageCourses\LanguageCourses;
use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Student\Student;
use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Work\Work;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class VisaAndResidentPermit extends Command
{
    public static $command = 'visa_and_resident_permit';

    public static $title = [
        'ru' => '👩‍💻 ВСЕ О ВИЗЕ/ВНЖ',
        'en' => '👩‍💻 EVERYTHING ABOUT A VISAS/RESIDENCE PERMIT'
    ];

    public static $usage = ['visa_and_resident_permit'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => "❓МОЕГО ВОПРОСА НЕТ В СПИСКЕ",
        ]);

        $buttons = BotApi::inlineKeyboard([
            [array(Student::getTitle('ru'), Student::$command, '')],
            [array(Work::getTitle('ru'), Work::$command,'')],
            [array(Business::getTitle('ru'), Business::$command,'')],
            [array(Family::getTitle('ru'), Family::$command,'')],
            [array(AfterUniversityVisa::getTitle('ru'), AfterUniversityVisa::$command,'')],
            [array(LanguageCourses::getTitle('ru'), LanguageCourses::$command,'')],
            [array("❓МОЕГО ВОПРОСА НЕТ В СПИСКЕ", LeaveContact::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "Выберите тему, о которой хотите узнать 🚀",
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