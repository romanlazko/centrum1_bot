<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use Carbon\Carbon;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SaveBirth extends Command
{
    public static $command = 'save_birth';

    public static $title = [
        'ru' => 'СОХРАНИТЬ ДАТУ',
        'en' => 'Save birth',
    ];

    public static $usage = ['save_birth'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());
        
        $this->getConversation()->update([
            'birth' => $telegram_chat->profile_birth
        ]);

        return $this->bot->executeCommand(StartDate::$command);
    }
}