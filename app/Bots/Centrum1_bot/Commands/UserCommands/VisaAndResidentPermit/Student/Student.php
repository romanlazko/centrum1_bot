<?php

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Student;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\VisaAndResidentPermit;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Student extends Command
{
    public static $command = 'student';

    public static $title = [
        'ru' => '📚 ВСЕ О СТУДЕНЧЕСКОЙ ВИЗЕ/ВНЖ',
        'en' => '📚 EVERYTHING ABOUT A STUDENT VISA/RESIDENCE PERMIT'
    ];

    public static $usage = ['student'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        // $this->getConversation()->update([
        //     'theme' => "❓МОЕГО ВОПРОСА НЕТ В СПИСКЕ",
        // ]);

        $buttons = BotApi::inlineKeyboard([
            [array(IWantStudentVisa::getTitle('ru'), IWantStudentVisa::$command, '')],
            [array(IveBeenExpelled::getTitle('ru'), IveBeenExpelled::$command, '')],
            [array(ImGettingDiploma::getTitle('ru'), ImGettingDiploma::$command, '')],
            [array(IWantChangePurposeOfStaying::getTitle('ru'), IWantChangePurposeOfStaying::$command, '')],
            [array(IWantExtendMyStudyVisa::getTitle('ru'), IWantExtendMyStudyVisa::$command, '')],
            [array(Nostrification::getTitle('ru'), Nostrification::$command, '')],
            // [array("❓МОЕГО ВОПРОСА НЕТ В СПИСКЕ", LeaveContact::$command, '')],
            [array("👈 НАЗАД", VisaAndResidentPermit::$command, '')],
        ]);

        $text = implode("\n\n", [
            "Студенческая виза — это разрешение на пребывание для обучения в ВУЗЕ. Она оформляется как виза типа D (код '23/24') или ВНЖ.",
            "По этой визе можно учиться, работать без дополнительных разрешений и путешествовать по Шенгену до 90 дней в 180-дневный период.",
            "Виза подлежит продлению при продолжении учебы. Это удобный вариант для получения образования и возможного дальнейшего трудоустройства в Чехии.",
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