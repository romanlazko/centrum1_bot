<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact;

use App\Bots\Centrum1_bot\Commands\UserCommands\HelpCommand;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Bots\Centrum1_bot\Commands\UserCommands\SendContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Send extends Command
{
    public static $command = 'l_send';

    public static $title = [
        'ru' => 'Отправить',
        'en' => 'Send',
    ];

    public static $usage = ['l_send'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $result = $this->bot->executeCommand(SendContact::$command);

        if ($result) {
            $buttons = BotApi::inlineKeyboard([
                [array("НАШИ КОНТАКТЫ", HelpCommand::$command, '')],
                [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
            ]);

            $text = implode("\n", [
                "Спасибо большое, Ваш запрос отправлен и будет обработан в ближайшее время."."\n",
            ]);
    
            $data = [
                'text'          =>  $text,
                'chat_id'       =>  $updates->getChat()->getId(),
                'reply_markup'  =>  $buttons,
                'parse_mode'    =>  'Markdown',
                'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
            ];
    
            $result = BotApi::returnInline($data);
        }

        return $result;
    }
}