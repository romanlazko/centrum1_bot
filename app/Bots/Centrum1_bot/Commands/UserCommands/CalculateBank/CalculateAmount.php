<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateBank;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class CalculateAmount extends Command
{
    public static $command = 'calculate_amount';

    public static $title = [
        'ru' => '💰 Подсчитать необходимую сумму',
        'en' => '💰 Calculate the amount you need'
    ];

    public static $usage = ['calculate_amount'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        return $this->bot->executeCommand(EndOfVisa::$command);
    }
}
