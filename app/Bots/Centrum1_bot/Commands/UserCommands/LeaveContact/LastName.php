<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact;

use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class LastName extends Command
{
    public static $command = 'l_last_name';

    public static $title = [
        'ru' => 'Фамилия',
        'en' => 'Last name',
    ];

    public static $usage = ['l_last_name'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitLastName::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [array("👈 НАЗАД", LeaveContact::$command, '')],
        ]);

        $text = implode("\n", [
            "Напишите пожалуйста свою фамилию:"
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