<?php

namespace App\Bots\Centrum1_bot\Commands\UserCommands\PMJ;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class PMJ extends Command
{
    public static $command = 'pmj';

    public static $title = [
        'ru' => '🗂️ ПМЖ',
        'en' => '🗂️ PMJ'
    ];

    public static $usage = ['pmj'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => "❓МОЕГО ВОПРОСА НЕТ В СПИСКЕ",
        ]);

        $buttons = BotApi::inlineKeyboard([
            [array(PMJExperience::getTitle('ru'), PMJExperience::$command, '')],
            [array(IWantPMJ::getTitle('ru'), IWantPMJ::$command, '')],
            [array(TurnkeyPMJ::getTitle('ru'), TurnkeyPMJ::$command, '')],
            [array(RetentPMJWhenLeaving::getTitle('ru'), RetentPMJWhenLeaving::$command, '')],
            [array("❓МОЕГО ВОПРОСА НЕТ В СПИСКЕ", LeaveContact::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n\n", [
            "🪪 Получение постоянного места жительства (ПМЖ) в Чехии — важный шаг для долгосрочного проживания в стране.",
            "Этот процесс включает в себя выполнение ряда требований, таких как постоянное проживание в Чехии в течение определенного времени, знание языка и наличие стабильных финансовых источников.",
            "Мы поможем вам разобраться в условиях, подготовить все необходимые документы и подать заявление на ПМЖ.",
            "Ниже выберете, пожалуйста, услугу которая вас интересует ⬇️",
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