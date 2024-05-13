<?php

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\AfterUniversityVisa;

use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\VisaAndResidentPermit;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AfterUniversityVisa extends Command
{
    public static $command = 'afteruniversityvisa';

    public static $title = [
        'ru' => '🎓 ОКОНЧИЛ ВУЗ ',
        'en' => '🎓 AFTER UNIVERSITY VISAS '
    ];

    public static $usage = ['afteruniversityvisa'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(IWantAfterUniversityVisa::getTitle('ru'), IWantAfterUniversityVisa::$command, '')],
            [array(IGotAfterUniversityVisa::getTitle('ru'), IGotAfterUniversityVisa::$command, '')],
            // [array(ElseQuestion::getTitle('ru'), ElseQuestion::$command, '')],
            [array("👈 НАЗАД", VisaAndResidentPermit::$command, '')],
        ]);

        $text = implode("\n\n", [
            "После окончания учебы в Чехии выпускники могут подать заявление на визу с целью поиска работы или начала индивидуального предпринимательства.",
            "Эта виза позволяет оставаться в стране сроком на 9 месяцев.",
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