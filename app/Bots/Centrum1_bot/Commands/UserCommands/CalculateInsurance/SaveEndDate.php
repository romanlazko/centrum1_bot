<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use Carbon\Carbon;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SaveEndDate extends Command
{
    public static $command = 'save_end_date';

    public static $title = [
        'ru' => 'СОХРАНИТЬ ДАТУ',
        'en' => 'Save end date',
    ];

    public static $usage = ['save_end_date'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'end_date' => Carbon::parse($updates->getInlineData()->getTemp())->addMonth()->format('Y-m'),
        ]);

        return $this->bot->executeCommand(Shengen::$command);
    }
}