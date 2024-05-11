<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Shengen extends Command
{
    public static $command = 'shengen';

    public static $title = [
        'ru' => 'ШЕНГЕН',
        'en' => 'Shengen',
    ];

    public static $usage = ['shengen'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array("ДА", Type::$command, '1')],
            [array("НЕТ, ХОЧУ СЭКОНОМИТЬ", Type::$command, '0')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ], 'shengen');

        $text = implode("\n", [
            "Отлично! У нас осталось всего два вопроса!"."\n",
            "*Важно ли для вас, чтобы страховка покрывала путешествия по зоне Шенген?*"
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