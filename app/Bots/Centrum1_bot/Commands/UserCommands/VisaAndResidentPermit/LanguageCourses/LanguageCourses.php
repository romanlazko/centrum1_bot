<?php

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\LanguageCourses;

use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\VisaAndResidentPermit;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class LanguageCourses extends Command
{
    public static $command = 'languagecourses';

    public static $title = [
        'ru' => '🇨🇿 ЯЗЫКОВЫЕ КУРСЫ И ЧАСТНЫЙ ВУЗ',
        'en' => '🇨🇿 LANGUAGE COURSES AND PRIVATE UNIVERSITY'
    ];

    public static $usage = ['languagecourses'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(LanguageCourses2324::getTitle('ru'), LanguageCourses2324::$command, '')],
            [array(LanguageCourses99::getTitle('ru'), LanguageCourses99::$command, '')],
            [array(PrivatUniversity::getTitle('ru'), PrivatUniversity::$command, '')],
            // [array(ElseQuestion::getTitle('ru'), ElseQuestion::$command, '')],
            [array("👈 НАЗАД", VisaAndResidentPermit::$command, '')],
        ]);

        $text = implode("\n\n", [
            "Языковые курсы для продления визы в Чехии должны быть аккредитованы Министерством образования.",
            "Эти курсы помогут улучшить знание чешского языка и соответствовать требованиям для продления вашей визы. Они также способствуют интеграции в общество Чехии.",
            "Поступление в аккредитованный частный вуз в Чехии предоставляет возможность для продления визы.",
            "Учебное заведение предлагает программы, которые соответствуют визовым требованиям и позволяют легально оставаться в стране. Начало обучение возможно от сентября и февраля.",
            "Обучение возможно на английским и чешском языке. При успешном окончании университета вы получите государственный диплом.",
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