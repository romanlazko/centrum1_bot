<?php

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Work;

use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\VisaAndResidentPermit;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Work extends Command
{
    public static $command = 'work';

    public static $title = [
        'ru' => '💼 ВСЕ О РАБОТЕ И РАБОЧЕЙ ВИЗЕ',
        'en' => '💼 ALL ABOUT WORK AND WORK VISAS'
    ];

    public static $usage = ['work'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(IWantWorkVisa::getTitle('ru'), IWantWorkVisa::$command, '')],
            [array(IveBeenFired::getTitle('ru'), IveBeenFired::$command, '')],
            [array(IWantChangeEmployer::getTitle('ru'), IWantChangeEmployer::$command, '')],
            [array(IWantExtendMyWorkVisa::getTitle('ru'), IWantExtendMyWorkVisa::$command, '')],
            // [array(ElseQuestion::getTitle('ru'), ElseQuestion::$command, '')],
            [array("👈 НАЗАД", VisaAndResidentPermit::$command, '')],
        ]);

        $text = implode("\n\n", [
            "Рабочая виза выдается для официального трудоустройства и относится к категории долгосрочного пребывания.",
            "Основные варианты — трудовая карта (zaměstnanecká karta) для работников и синяя карта (modrá karta) для высококвалифицированных специалистов.",
            "Для получения визы требуется трудовой договор с чешским работодателем, разрешение на работу (если необходимо), подтверждение жилья и анкета.",
            "Важно соблюдать все требования, так как нарушение визового режима может привести к отказу в получении.",
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