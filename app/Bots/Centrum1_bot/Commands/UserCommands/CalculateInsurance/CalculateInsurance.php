<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\BetterQuality\BetterQuality;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateByCompany\CalculateByCompany;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\ContinuingTreatment\ContinuingTreatment;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\LowestCost\LowestCost;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\PriceAndQuality\PriceAndQuality;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Events\ChatStartCalculatingInsurance;
use App\Jobs\SendNotificationToFinishCalculatingInsurance;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class CalculateInsurance extends Command
{
    public static $command = 'calculate_insurance';

    public static $title = [
        'ru' => 'ПОСЧИТАТЬ СТРАХОВКУ',
        'en' => 'CALCULATE INSURANCE'
    ];

    public static $usage = ['calculate_insurance'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(CalculateByCompany::getTitle('ru'), CalculateByCompany::$command, '')],
            [array(LowestCost::getTitle('ru'), LowestCost::$command, '')],
            [array(PriceAndQuality::getTitle('ru'), PriceAndQuality::$command, '')],
            [array(BetterQuality::getTitle('ru'), BetterQuality::$command, '')],
            [array(ContinuingTreatment::getTitle('ru'), ContinuingTreatment::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "Для начала давайте определимся, какой критерий для вас самый важный и какая страховка вам нужна!👆"."\n",

            "Также обратите внимание, что если вы проходите актуально лечение по своей действующей страховке, это очень важно!"."\n",
            
            "Мне нужна⬇"
        ]);

        $telegram_chat = DB::getChat($updates->getChat()->getId());

        event(new ChatStartCalculatingInsurance($telegram_chat->id));

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