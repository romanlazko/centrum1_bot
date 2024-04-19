<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Type extends Command
{
    public static $command = 'type';

    public static $title = [
        'ru' => 'Посчитать страховку',
        'en' => 'Calculate Insurance'
    ];

    public static $usage = ['type'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array("Я СТУДЕНТ/КА", Calculate::$command, 'student')],
            [array("Я БЕРЕМЕННА ИЛИ ПЛАНИРУЮ", Calculate::$command, 'pregnant')],
            [array("Я ПРОФЕССИОНАЛЬНЫЙ СПОРТСМЕН/КА", Calculate::$command, 'sport')],
            [array("НЕ ПОПАДАЮ НИ В ОДНУ КАТЕГОРИЮ", Calculate::$command, 'standart')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ], 'type');

        $text = implode("\n", [
            "Отлично, и последнее, что нам нужно уточнить, это попадаете ли вы под специальную категорию, чтобы мы подобрали продукт, который нужен именно вам?"
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