<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\Shengen;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\StartDate;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;

class Applying extends Command
{
    public static $command = 'ci_applying';

    public static $usage = ['ci_applying'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        // $counter = BotApi::Counter($updates->getInlineData()->getApplying(), Shengen::$command, 3, 24);

        $count = $updates->getInlineData()->getApplying();

        $counter = [
			array('<', 						Applying::$command, 		$count > 3 ? $count-1 : 3), 
			array($count, 					Shengen::$command,          $count), 
			array('>', 						Applying::$command, 		$count < 24 ? $count+1 : 24)
		];

        $buttons = BotApi::inlineKeyboard([
            $counter,
            [array('11', Shengen::$command, '11')],
            [array('9', Shengen::$command, '9')],
            [array('6', Shengen::$command, '6')],
            [array('3', Shengen::$command, '3')],
            [
                array("👈 Назад", StartDate::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, ''),
            ]
        ], 'applying');

        $text = implode("\n", [
            "*КАК ВЫ МОЖЕТЕ СЭКОНОМИТЬ*⬇️"."\n",

            "Часто иностранцы переплачивают за ненужные месяца страховки!"."\n",

            "Обратите внимание до когда ваша страховка, возможно вам достаточно новой на более короткий срок!"."\n",

            "_Пример: ваша страховка до 30.09.2024",
            "Виза будет с 01.09.2024 до 31.08.2025",
            "Вам нужна страховка на 11 месяцев_"."\n",
		    "На какой срок (*В МЕСЯЦАХ*) вам нужна *новая страховка*",
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
