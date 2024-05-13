<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateByCompany;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\AwaitBirth;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateInsurance;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\ContinuingTreatment\SaveInsurance;
use App\Bots\Centrum1_bot\Commands\UserCommands\ContactManager;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class CalculateByCompany extends Command
{
    public static $command = 'calculate_by_company';

    public static $title = [
        'ru' => 'ПОСЧИТАТЬ КОНКРЕТНУЮ КОМПАНИЮ',
        'en' => 'CALCULATE BY COMPANY',
    ];

    public static $usage = ['calculate_by_company'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitBirth::$expectation);

        $this->getConversation()->update([
            'criterium' => 'continuing_treatment'
        ]);

        $text = implode("\n", [
            "Если вы уже знаете, какая компания лучше всего подходит вам, то давайте посчитаем цену на страховку, со всеми скидками и бонусами."."\n",

            "*Для начала выберите компанию, которая вас интересует⬇*",
        ]);

        $buttons = BotApi::inlineKeyboard([
            [array(ContactManager::getTitle('ru'), ContactManager::$command, '')],
            [array("MAXIMA", SaveInsurance::$command, 'maxima')],
            [array("PVZP", SaveInsurance::$command, 'pvzp')],
            [array("SLAVIA", SaveInsurance::$command, 'slavia')],
            [array("COLONNADE", SaveInsurance::$command, 'colonade')],
            [array("SV (EX. ERGO)", SaveInsurance::$command, 'sv')],
            [array("👈 НАЗАД", CalculateInsurance::$command, '')],
        ], 'current_insurance');

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