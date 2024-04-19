<?php 

namespace App\Bots\strachovanie1_bot\Commands\UserCommands\CalculateInsurance\ContinuingTreatment;

use App\Bots\strachovanie1_bot\Commands\UserCommands\CalculateInsurance\AwaitBirth;
use App\Bots\strachovanie1_bot\Commands\UserCommands\CalculateInsurance\BirthCommand;
use App\Bots\strachovanie1_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SaveInsurance extends Command
{
    public static $command = 'save_insurance';

    public static $title = [];

    public static $usage = ['save_insurance'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'current_insurance' => $updates->getInlineData()->getCurrentInsurance()
        ]);

        return $this->bot->executeCommand(BirthCommand::$command);
    }
}