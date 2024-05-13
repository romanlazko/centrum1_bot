<?php

namespace App\Bots\Centrum1_bot\Commands\UserCommands\PMJ;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IWantPMJ extends Command
{
    public static $command = 'iwantpmj';

    public static $title = [
        'ru' => 'Получение ПМЖ',
        'en' => 'Get PMJ'
    ];

    public static $usage = ['iwantpmj'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);

        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'iwantpmj')],
            [array("👈 НАЗАД", PMJ::$command, '')],
        ]);

        $text = implode("\n\n", [
            "Получение постоянного места жительства (ПМЖ) в Чехии позволяет вам жить и работать в стране без ограничений на протяжении 10 лет.",
            "Для этого необходимо выполнить несколько условий, таких как проживание в Чехии на протяжении нескольких лет, знание языка и наличие стабильных финансов.",
            "Необходимо собрать пакет документов, правильно подсчитать стаж и подготовиться к подаче заявления.",
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