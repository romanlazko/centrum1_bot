<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

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
        'ru' => 'Количество месяцев',
        'en' => 'Count of month',
    ];

    public static $usage = ['end_date'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitEndDate::$expectation);

        $end_date = Carbon::parse($this->getConversation()->notes['start_date']);

        $buttons = BotApi::inlineKeyboard([
            [array("СВЯЗАТЬСЯ С МЕНЕДЖЕРОМ", MenuCommand::$command, '')],
            [array($end_date->clone()->addMonth(11)->subDay()->format('d.m.Y'), SaveEndDate::$command, $end_date->clone()->addMonth(11)->subDay()->format('d.m.Y'))],
            [array($end_date->clone()->addMonth(12)->subDay()->format('d.m.Y'), SaveEndDate::$command, $end_date->clone()->addMonth(12)->subDay()->format('d.m.Y'))],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "Напишите пожалуйста дату *ДО какого числа вам нужна новая страховка*, в формате ДД.ММ.ГГГГ:"
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