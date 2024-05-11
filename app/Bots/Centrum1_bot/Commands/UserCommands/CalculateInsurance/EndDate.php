<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use App\Bots\Centrum1_bot\Commands\UserCommands\ContactManager;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Carbon\Carbon;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class EndDate extends Command
{
    public static $command = 'end_date';

    public static $title = [
        'ru' => 'КОЛИЧЕСТВО МЕСЯЦЕВ',
        'en' => 'Count of month',
    ];

    public static $usage = ['end_date'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        // $updates->getFrom()->setExpectation(AwaitEndDate::$expectation);

        // $end_date = Carbon::parse($this->getConversation()->notes['start_date']);

        // $buttons = BotApi::inlineKeyboard([
        //     [array(ContactManager::getTitle('ru'), ContactManager::$command, '')],
        //     // [array($end_date->clone()->addMonth(11)->subDay()->format('d.m.Y'), SaveEndDate::$command, $end_date->clone()->addMonth(11)->subDay()->format('d.m.Y'))],
        //     // [array($end_date->clone()->addMonth(12)->subDay()->format('d.m.Y'), SaveEndDate::$command, $end_date->clone()->addMonth(12)->subDay()->format('d.m.Y'))],
        //     [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        // ], 'temp');

        $end_date = Carbon::parse($updates->getInlineData()->getTemp());

        $mounth = [
			array('<', 						    EndDate::$command, 		$end_date->clone()->modify('-1 month')->format('Y-m')), 
			array($end_date->format('M Y'), 	SaveEndDate::$command,    $end_date->format('Y-m')), 
			array('>', 						    EndDate::$command, 		$end_date->clone()->modify('+1 month')->format('Y-m'))
		];

        $buttons = BotApi::inlineKeyboard([
            $mounth,
            [array($end_date->subMonth()->format('M Y'), SaveEndDate::$command, $end_date->format('Y-m'))],
            [array($end_date->subMonth()->format('M Y'), SaveEndDate::$command, $end_date->format('Y-m'))],
            [array($end_date->subMonth()->format('M Y'), SaveEndDate::$command, $end_date->format('Y-m'))],
            [
                array("👈 НАЗАД", MenuCommand::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, ''),
            ]
        ], 'temp');

        $text = implode("\n", [
            "Напишите пожалуйста *В КАКОМ МЕСЯЦЕ ДОЛЖНА ЗАКОНЧИТЬСЯ новая страховка*:"
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