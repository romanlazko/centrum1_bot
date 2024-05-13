<?php

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Business;

use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\VisaAndResidentPermit;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Business extends Command
{
    public static $command = 'business';

    public static $title = [
        'ru' => '🌟 ВСЕ О ПРЕДПРИНИМАТЕЛЬСТВЕ',
        'en' => '🌟 EVERYTHING ABOUT A BUSINESS/ENTREPRENEUR WORK'
    ];

    public static $usage = ['business'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(IWantBusinessVisa::getTitle('ru'), IWantBusinessVisa::$command, '')],
            [array(IWantOpenBusiness::getTitle('ru'), IWantOpenBusiness::$command, '')],
            [array(IWantExtendMyBusinessVisa::getTitle('ru'), IWantExtendMyBusinessVisa::$command, '')],
            // [array(ElseQuestion::getTitle('ru'), ElseQuestion::$command, '')],
            [array("👈 НАЗАД", VisaAndResidentPermit::$command, '')],
        ]);

        $text = implode("\n\n", [
            "Открыть индивидуальное предпринимательство (Živnostenské oprávnění) в Чехии можно как гражданам ЕС, так и иностранцам с любой долгосрочной визой.",
            "Однако в зависимости от формы вашего ип или фирмы может отличаться список документов для регистрации.",
            "Предпринимательская виза позволяет работать как индивидуальный предприниматель, но податься на нее можно только после 5 лет проживания.",
            "Исключение составляют выпускники чешских вузов, для которых это правило не действует.",
            "Важно, чтобы у вас было успешное ИП или фирма со стабильным доходом, покрывающим все расходы.",
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