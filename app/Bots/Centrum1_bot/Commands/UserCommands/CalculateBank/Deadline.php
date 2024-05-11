<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateBank;

use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Carbon\Carbon;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;

class Deadline extends Command
{
    public static $command = 'deadline';

    public static $usage = ['deadline'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $end_of_visa    = Carbon::parse($updates->getInlineData()->getEndOfVisa());
        $deadline       = Carbon::parse($updates->getInlineData()->getDeadline());

        $mounth = BotApi::MonthCounter($deadline, Calculate::$command);
        $now    = Carbon::now();

        $buttons = BotApi::inlineKeyboard([
            $mounth,
            [array($now->addMonth()->format('M Y'), Calculate::$command, $now->format('Y-m-d'))],
            [array($now->addMonth()->format('M Y'), Calculate::$command, $now->format('Y-m-d'))],
            [array($now->addMonth()->format('M Y'), Calculate::$command, $now->format('Y-m-d'))],
            [
                array("👈 НАЗАД", Applying::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, ''),
            ]
        ], 'deadline');

        $text = implode("\n", [
            "*Месяц подачи на визу*"."\n",
		    "В каком месяце Вы планируете подаваться на продление своей *текущей визы* \ *долгосрочного пребываения*",
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
