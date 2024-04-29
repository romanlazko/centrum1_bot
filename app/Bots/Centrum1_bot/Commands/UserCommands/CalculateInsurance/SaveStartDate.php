<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SaveStartDate extends Command
{
    public static $command = 'save_start_date';

    public static $title = [
        'ru' => 'Сохранить дату',
        'en' => 'Save start date',
    ];

    public static $usage = ['save_start_date'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'start_date' => $updates->getInlineData()->getTemp(),
        ]);

        return $this->bot->executeCommand(EndDate::$command);
    }
}