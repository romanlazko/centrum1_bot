<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
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
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $situation =$this->getConversation()->notes['situation'] ?? null;
        $theme = $this->getConversation()->notes['theme'] ?? null;

        $buttons = BotApi::inlineKeyboardWithLink(
            array('text' => 'КОНТАКТ', 'url'  => "tg://user?id={$updates->getChat()->getId()}")
        );

        $text = implode("\n", [
            "*Клиент отправил запрос на связь с менеджером:*"."\n",
            "Пользователь: *{$updates->getChat()->getFirstName()} {$updates->getChat()->getLastName()}*",
            "ID: *{$updates->getChat()->getId()}*",
            "Username: *@{$updates->getChat()->getUsername()}*"."\n",
            "Заполненные данные:",
            "ФИО: *{$telegram_chat->profile_first_name} {$telegram_chat->profile_last_name}*",
            "Телефон: *{$telegram_chat->profile_phone}*",
            "Описание ситуации: *{$situation}*",
            "Тема: *{$theme}*"
        ]);

        $data = [
            'text'          =>  $text,
            'chat_ids'       =>  ['1019462085', '544883527'],
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
        ];

        return BotApi::sendMessages($data);
    }
}