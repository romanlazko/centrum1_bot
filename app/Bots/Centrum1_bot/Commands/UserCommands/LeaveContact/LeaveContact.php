<?php

namespace App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact;

use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class LeaveContact extends Command
{
    public static $command = 'leave_contacts';

    public static $title = [
        'ru' => '📞 ОСТАВИТЬ КОНТАКТ',
        'en' => '📞 LEAVE A CONTACT'
    ];

    public static $usage = ['leave_contacts'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $buttons = BotApi::inlineKeyboard([
            [array("ИМЯ: ".($telegram_chat->profile_first_name ?? null), FirstName::$command, '')],
            [array("ФАМИЛИЯ: ".($telegram_chat->profile_last_name ?? null), LastName::$command, '')],
            [array("ТЕЛЕФОН: ".($telegram_chat->profile_phone ?? null), Phone::$command, '')],
            [array("ТЕЗИСНО ОПИШИТЕ СИТУАЦИЮ: ".($this->getConversation()->notes['situation'] ?? null), Situation::$command, '')],
            [
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, ''),
                array("✅ ОТПРАВИТЬ", Send::$command, '')
            ],
        ]);

        $text = implode("\n", [
            "Укажите пожалуйста свои данные для обратной связи:"
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