<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SaveEndDate extends Command
{
    public static $command = 'save_end_date';

    public static $title = [
        'ru' => 'Сохранить дату',
        'en' => 'Save end date',
    ];

    public static $usage = ['save_end_date'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'end_date' => $updates->getInlineData()->getTemp(),
        ]);

        return $this->bot->executeCommand(Shengen::$command);
    }
}