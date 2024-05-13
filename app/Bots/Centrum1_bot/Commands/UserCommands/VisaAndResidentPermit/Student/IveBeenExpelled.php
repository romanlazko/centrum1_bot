<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Student;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\LanguageCourses\LanguageCourses2324;
use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\LanguageCourses\LanguageCourses99;
use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\LanguageCourses\PrivatUniversity;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IveBeenExpelled extends Command
{
    public static $command = 'ivebeengeelled';

    public static $title = [
        'ru' => 'Меня отчислили, что делать?',
        'en' => 'I have been expelled, what next?',
    ];

    public static $usage = ['ivebeengeelled'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LanguageCourses2324::getTitle('ru'), LanguageCourses2324::$command, '')],
            [array(LanguageCourses99::getTitle('ru'), LanguageCourses99::$command, '')],
            [array(PrivatUniversity::getTitle('ru'), PrivatUniversity::$command, '')],
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, '')],
            [array("👈 НАЗАД", Student::$command, '')],
        ], 'type');

        $text = implode("\n", [
            "Если вам нужна помощь с сохранением визы после отчисления, важно помнить, что для этого необходимо иметь законную цель пребывания."."\n",
            "Вы можете сохранить вашу цель пребывания записавшись на языковые курсы либо поступление в частый вуз."."\n",
            "Языковые курсы помогут улучшить знание чешского языка и соответствовать требованиям для продления вашей визы. Они также способствуют интеграции в общество Чехии."."\n",
            "Поступление в аккредитованный частный вуз в Чехии предоставляет возможность для продления визы."."\n",
            "Учебное заведение предлагает программы, которые соответствуют визовым требованиям и позволяют легально оставаться в стране."."\n",
            "Начало обучения возможно от сентября и февраля. При успешном окончании университета вы получите государственный диплом."."\n",

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