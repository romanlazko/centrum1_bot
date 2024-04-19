<?php 

namespace App\Bots\strachovanie1_bot\Commands\UserCommands\CalculateInsurance;

use App\Bots\strachovanie1_bot\Commands\UserCommands\CalculateInsurance\BetterQuality\CalculateBetterQuality;
use App\Bots\strachovanie1_bot\Commands\UserCommands\CalculateInsurance\ContinuingTreatment\CalculateContinuingTreatment;
use App\Bots\strachovanie1_bot\Commands\UserCommands\CalculateInsurance\LowestCost\CalculateLowestCost;
use App\Bots\strachovanie1_bot\Commands\UserCommands\CalculateInsurance\LowestCost\LowestCost;
use App\Bots\strachovanie1_bot\Commands\UserCommands\CalculateInsurance\PriceAndQuality\CalculatePriceAndQuality;
use App\Bots\strachovanie1_bot\Commands\UserCommands\MenuCommand;
use App\Models\Colonnade;
use App\Models\Maxima;
use App\Models\Slavia;
use App\Models\VZP;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Calculate extends Command
{
    public static $command = 'calculate';

    public static $title = [
        'ru' => 'Посчитать страховку',
        'en' => 'Calculate Insurance'
    ];

    public static $usage = ['calculate'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $criterion = $this->getConversation()->notes['criterium'];

        return match ($criterion) {
            'lowest_cost' => $this->bot->executeCommand(CalculateLowestCost::$command),
            'price_and_quality' => $this->bot->executeCommand(CalculatePriceAndQuality::$command),
            'continuing_treatment' => $this->bot->executeCommand(CalculateContinuingTreatment::$command),
            'better_quality' => $this->bot->executeCommand(CalculateBetterQuality::$command),
            default => $this->bot->executeCommand(MenuCommand::$command),
        };
        
    }
}