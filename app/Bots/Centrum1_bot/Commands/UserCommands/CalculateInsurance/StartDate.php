<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use App\Bots\Centrum1_bot\Commands\UserCommands\ContactManager;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Carbon\Carbon;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class StartDate extends Command
{
    public static $command = 'start_date';

    public static $title = [
        'ru' => 'НАЧАЛО СТРАХОВКИ',
        'en' => 'Start of insurance',
    ];

    public static $usage = ['start_date'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitStartDate::$expectation);

        // $start_date = Carbon::create(now()->year, 9, 1);

        $buttons = BotApi::inlineKeyboard([
            [array(ContactManager::getTitle('ru'), ContactManager::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ], 'temp');

        // $start_date = Carbon::parse($updates->getInlineData()->getTemp());

        // $mounth = [
		// 	array('<', 						    StartDate::$command, 		$start_date->clone()->modify('-1 month')->format('Y-m')), 
		// 	array($start_date->format('M Y'), 	SaveStartDate::$command,    $start_date->format('Y-m')), 
		// 	array('>', 						    StartDate::$command, 		$start_date->clone()->modify('+1 month')->format('Y-m'))
		// ];

        // $now    = Carbon::now();

        // $buttons = BotApi::inlineKeyboard([
        //     $mounth,
        //     [array($now->addMonth()->format('M Y'), SaveStartDate::$command, $now->format('Y-m'))],
        //     [array($now->addMonth()->format('M Y'), SaveStartDate::$command, $now->format('Y-m'))],
        //     [array($now->addMonth()->format('M Y'), SaveStartDate::$command, $now->format('Y-m'))],
        //     [array($now->addMonth()->format('M Y'), SaveStartDate::$command, $now->format('Y-m'))],
        //     [
        //         array("👈 Назад", MenuCommand::$command, ''),
        //         array(MenuCommand::getTitle('ru'), MenuCommand::$command, ''),
        //     ]
        // ], 'temp');

        $text = implode("\n", [
            "Теперь давайте посчитаем срок, на который вам нужна страховка."."\n",
            "Напишите пожалуйста *от какого числа должна начаться новая страховка*:"."\n",
            "_Обычно это следующий месяц, после окончания актуальной страховки_"
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