<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use Carbon\Carbon;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SaveStartDate extends Command
{
    public static $command = 'save_start_date';

    public static $title = [
        'ru' => 'СОХРАНИТЬ ДАТУ',
        'en' => 'Save start date',
    ];

    public static $usage = ['save_start_date'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        

        $this->getConversation()->update([
            'start_date' => $updates->getInlineData()->getTemp(),
        ]);

        $updates->getInlineData()->setTemp(Carbon::parse($updates->getInlineData()->getTemp())->addYear()->subMonth()->format('Y-m'));

        return $this->bot->executeCommand(EndDate::$command);
    }
}