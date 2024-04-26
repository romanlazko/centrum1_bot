<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SendContact extends Command
{
    public static $command = 'send_contact';

    public static $title = [
        'ru' => 'СВЯЗАТЬСЯ С МЕНЕДЖЕРОМ',
        'en' => 'CONTACT MANAGER',
    ];

    public static $usage = ['send_contact'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboardWithLink(
            array('text' => 'Контакт', 'url'  => "tg://user?id={$updates->getChat()->getId()}")
        );

        $text = implode("\n", [
            "*Клиент отправил запрос на связь с менеджером:*"."\n",
            "Имя: *{$updates->getChat()->getFirstName()}*",
            "Фамилия: *{$updates->getChat()->getLastName()}*",
            "ID: *{$updates->getChat()->getId()}*",
            "Username: *@{$updates->getChat()->getUsername()}*",
        ]);

        $data = [
            'text'          =>  $text,
            'chat_id'       =>  '1019462085',
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
        ];

        return BotApi::sendMessage($data);
    }
}