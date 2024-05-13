<?php

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Family;

use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\VisaAndResidentPermit;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Family extends Command
{
    public static $command = 'family';

    public static $title = [
        'ru' => '👥 ВОССОЕДИНЕНИЕ С СЕМЬЕЙ/ПАРТНЕРСТВО',
        'en' => '👥 EVERYTHING ABOUT FAMILY AND PARTNER RESIDENCE'
    ];

    public static $usage = ['family'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(RegistrationOfMarriage::getTitle('ru'), RegistrationOfMarriage::$command, '')],
            [array(IWantFamilyVisa::getTitle('ru'), IWantFamilyVisa::$command, '')],
            [array(IWantExtendMyFamilyVisa::getTitle('ru'), IWantExtendMyFamilyVisa::$command, '')],
            [array(IWantTemporaryResidence::getTitle('ru'), IWantTemporaryResidence::$command, '')],
            [array(IWantExtendMyTemporaryResidence::getTitle('ru'), IWantExtendMyTemporaryResidence::$command, '')],
            // [array(ElseQuestion::getTitle('ru'), ElseQuestion::$command, '')],
            [array("👈 НАЗАД", VisaAndResidentPermit::$command, '')],
        ]);

        $text = implode("\n\n", [
            "Оформление брака в Чехии происходит через ЗАГС либо консульство и доступно как для граждан страны, так и для иностранцев.",
            "Процедура включает подачу заявления, оплату госпошлины и назначение даты церемонии.",
            "Виза по воссоединению с семьей- позволяет проживать в Чехии с близкими родственниками, имеющими ВНЖ, ПМЖ или гражданство ЕС.",
            "После подачи заявления процесс рассмотрения может занять несколько месяцев, в случае одобрения виза дает право на проживание и в некоторых случаях работу без ограничений.",
            "Этот вариант часто используется для долгосрочного переезда.",
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