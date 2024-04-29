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
        'ru' => 'Начало страховки',
        'en' => 'Start of insurance',
    ];

    public static $usage = ['start_date'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitStartDate::$expectation);

        $start_date = Carbon::create(now()->year, 9, 1);

        $buttons = BotApi::inlineKeyboard([
            [array(ContactManager::getTitle('ru'), ContactManager::$command, '')],
            // [array($start_date->format('d.m.Y'), SaveStartDate::$command, $start_date->format('d.m.Y'))],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ], 'temp');

        $text = implode("\n", [
            "Теперь давайте посчитаем срок, на который вам нужна страховка."."\n",
            "Напишите пожалуйста *ОТ какого числа вам нужна новая страховка*, в формате ДД.ММ.ГГГГ:"."\n",
            "_Обычно это следующий день, после окончания актуальной страховки_"
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