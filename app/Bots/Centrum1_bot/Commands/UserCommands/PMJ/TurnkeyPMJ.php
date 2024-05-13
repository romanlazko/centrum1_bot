<?php

namespace App\Bots\Centrum1_bot\Commands\UserCommands\PMJ;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class TurnkeyPMJ extends Command
{
    public static $command = 'turnkeypmj';

    public static $title = [
        'ru' => 'ПМЖ под ключ',
        'en' => 'Turnkey PMJ'
    ];

    public static $usage = ['turnkeypmj'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'turnkeypmj')],
            [array("👈 НАЗАД", PMJ::$command, '')],
        ]);

        $text = implode("\n\n", [
            "Получение ПМЖ в Чехии под ключ — это комплексная услуга, которая включает все этапы, от подачи заявки до получения статуса.",
            "Мы поможем вам разобраться в требованиях, собрать необходимые документы, подсчитать стаж и выполнить все юридические процедуры.",
            "Вы получите полную поддержку на каждом шаге, чтобы процесс получения ПМЖ был быстрым и без лишних трудностей.",
            "*Нужна более подробная консультация по этой услуге?*\n*Ниже оставьте пожалуйста ваши контакты, чтобы менеджер с вами связался ☎️*",
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