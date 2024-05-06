<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateBank;

use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Bots\Centrum1_bot\Config;
use Carbon\Carbon;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Commands\UserCommands\AdvertisementCommand;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;

class EndOfVisa extends Command
{
    public static $command = 'end_of_visa';

    public static $usage = ['end_of_visa'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $mounth = BotApi::MonthCounter(Carbon::parse($updates->getInlineData()->getEndOfVisa()), Applying::$command);
        $now    = Carbon::now();

        $buttons = BotApi::inlineKeyboard([
            $mounth,
            [array($now->addMonth()->format('M Y'), Applying::$command, $now->format('Y-m-d'))],
            [array($now->addMonth()->format('M Y'), Applying::$command, $now->format('Y-m-d'))],
            [array($now->addMonth()->format('M Y'), Applying::$command, $now->format('Y-m-d'))],
            [
                array("👈 Назад", MenuCommand::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, ''),
            ]
        ], 'end_of_visa');

        $text = implode("\n", [
            "*Окончание визы*"."\n",
		    "Выберите месяц, в котором у Вас заканчивается *текущая виза* \ *предполагаемая виза* \ *долгосрочное пребывание*",
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
