<?php

namespace App\Bots\Centrum1_bot\Commands\UserCommands\PMJ;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class PMJExperience extends Command
{
    public static $command = 'pmjexperience';

    public static $title = [
        'ru' => 'Посчитать стаж для ПМЖ',
        'en' => 'Calculate the experience for PMJ'
    ];

    public static $usage = ['pmjexperience'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'pmjexperience')],
            [array("👈 НАЗАД", PMJ::$command, '')],
        ]);

        $text = implode("\n\n", [
            "Подсчет стажа для получения ПМЖ — это важный этап, который требует учета вашего законного проживания и работы в стране.",
            "Необходимо точно рассчитать, сколько времени вы проживали на территории Чехии, включая работу, учебу и другие формы легального пребывания, также учитываются ваши выезды за пределы страны.",
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