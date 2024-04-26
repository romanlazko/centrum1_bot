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
        'ru' => 'СВЯЗАТЬСЯ С МЕНЕДЖЕРОМ',
        'en' => 'CONTACT MANAGER',
    ];

    public static $usage = ['contact_manager'];

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
            "Username: *{$updates->getChat()->getUsername()}*",
        ]);

        $data = [
            'text'          =>  $text,
            'chat_ids'       =>  ['372440193', '544883527', '1019462085', '538296130'],
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        $result = BotApi::sendMessages($data);

        if ($result->getOk()) {
            return BotApi::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text' => "Спасибо за ваш запрос. Наши менеджеры уже спешат вам помочь 👩‍💻",
                'show_alert'        => true
            ]);
        }
    }
}