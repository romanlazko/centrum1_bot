<?php

namespace App\Bots\Centrum1_bot\Commands\UserCommands\PMJ;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class RetentPMJWhenLeaving extends Command
{
    public static $command = 'retentpmjwhenleaving';

    public static $title = [
        'ru' => 'Сохрание ПМЖ при уезде из Чехии',
        'en' => 'Retent PMJ when leaving CZE'
    ];

    public static $usage = ['retentpmjwhenleaving'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'retentpmjwhenleaving')],
            [array("👈 НАЗАД", PMJ::$command, '')],
        ]);

        $text = implode("\n\n", [
            "Сохранение ПМЖ при временном отъезде из Чехии возможно, но важно не нарушать установленные правила, такие как ограничения на срок пребывания за пределами страны.",
            "Кроме того, вы обязаны иметь в Чехии определенные документы, такие как подтверждение жилья, чтобы в случае необходимости вернуться без проблем.",
            "*Нужна более подробная консультация?*\n*Ниже оставьте, пожалуйста, ваши контакты, чтобы менеджер с вами связался ☎️*",
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