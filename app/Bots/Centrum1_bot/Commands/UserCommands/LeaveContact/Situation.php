<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact;

use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Situation extends Command
{
    public static $command = 'l_situation';

    public static $title = [
        'ru' => 'Опишите свою ситуацию',
        'en' => 'Situation',
    ];

    public static $usage = ['l_situation'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitSituation::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [array("👈 НАЗАД", LeaveContact::$command, '')],
        ]);

        $text = implode("\n", [
            "Пожалуйста, кратко опишите свою ситуацию:"
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