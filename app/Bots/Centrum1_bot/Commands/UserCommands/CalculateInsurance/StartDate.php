<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class StartDate extends Command
{
    public static $command = 'start_date';

    public static $title = [
        'ru' => 'Начало страховки',
        'en' => 'Start of insurance',
    ];

    public static $usage = ['start_date'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitStartDate::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [array("СВЯЗАТЬСЯ С МЕНЕДЖЕРОМ", MenuCommand::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "Теперь давайте посчитаем срок, на который вам нужна страховка."."\n",
            "*Напишите пожалуйста дату от которой вам нужна новая страховка, в формате ДД.ММ.ГГГГ:*"
        ]);

        $data = [
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        return BotApi::returnInline($data);
    }
}