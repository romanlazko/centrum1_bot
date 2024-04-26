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
            array('text' => "ОФОРМИТЬ СТРАХОВКУ", 'web_app' => ['url' => 'https://forms.amocrm.ru/rvcmwdc']),
            [
                [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
            ],
        );

        $data = [
            'text'          =>  "Спасибо за ваш запрос, я отправляю его нашим менеджерам! Они свяжутся с вами в ближайшее время!👩‍💻",
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        $result = BotApi::sendMessage($data);

        if ($result->getOk()) {
            return $this->bot->executeCommand(SendContact::$command);
        }
    }
}