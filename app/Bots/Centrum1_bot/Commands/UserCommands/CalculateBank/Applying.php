<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateBank;

use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;

class Applying extends Command
{
    public static $command = 'applying';

    public static $usage = ['applying'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $counter = BotApi::Counter($updates->getInlineData()->getApplying(), Deadline::$command, 3, 24);

        $buttons = BotApi::inlineKeyboard([
            $counter,
            [array('9', Deadline::$command, '9')],
            [array('6', Deadline::$command, '6')],
            [array('3', Deadline::$command, '3')],
            [
                array("👈 Назад", EndOfVisa::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, ''),
            ]
        ], 'applying');

        $text = implode("\n", [
            "*Срок продления*"."\n",
		    "На какой срок (*В МЕСЯЦАХ*) Вы продлеваете свою *текущую визу* \ *долгосрочное пребываение*",
        ]);

        try {
            return BotApi::returnInline([
                'text'          =>  $text,
                'chat_id'       =>  $updates->getChat()->getId(),
                'reply_markup'  =>  $buttons,
                'parse_mode'    =>  'Markdown',
                'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
            ]);
        }
        catch(TelegramException $e){
            return BotApi::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text'              => "Не торопись",
                'show_alert'        => true
            ]);
        }
    }
}
