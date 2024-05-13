<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\Profile;

use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Phone extends Command
{
    public static $command = 'phone';

    public static $title = [
        'ru' => 'Телефон',
        'en' => 'Phone',
    ];

    public static $usage = ['phone'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitPhone::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [array("👈 НАЗАД", Profile::$command, '')],
        ]);

        $text = implode("\n", [
            "Напишите пожалуйста свой телефон:"
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