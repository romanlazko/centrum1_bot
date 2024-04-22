<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class ContactManager extends Command
{
    public static $command = 'contact_manager';

    public static $title = [
        'ru' => 'Связь с менеджером',
        'en' => 'Contact manager',
    ];

    public static $usage = ['contact_manager'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboardWithLink([
            array('text' => 'Написать менеджеру', 'url' => 'https://t.me/centr1_cz')
        ],
        [
            [array(MenuCommand::getTitle('en'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "Менеджеру было отправлено уведомление о том что вы хотите связаться с нами."."\n",
            "Вы в любой момент можете сами написать нам вопрос, это ускорит ответ.", 
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