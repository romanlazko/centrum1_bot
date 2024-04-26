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
        $buttons = BotApi::inlineKeyboardWithLink(
            array('text' => 'Контакт', 'url'  => "tg://user?id={$updates->getChat()->getId()}")
        );

        $text = implode("\n", [
            "Клиент отправил запрос на связь с менеджером.", 
        ]);

        $data = [
            'text'          =>  $text,
            'chat_ids'       =>  ['372440193', '544883527'],
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        $result = BotApi::sendMessages($data);

        if ($result->isOk()) {
            return BotApi::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text' => "Спасибо за ваш запрос. Наши менеджеры уже спешат вам помочь 👩‍💻",
            ]);
        }
    }
}