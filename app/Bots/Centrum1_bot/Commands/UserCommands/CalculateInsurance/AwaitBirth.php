<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitBirth extends Command
{
    public static $expectation = 'await_birth';

    public static $pattern = '/^await_birth$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $text = $updates->getMessage()?->getText();

        if ($text === null OR !preg_match('/^(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[0-2])\.\d{4}$/', $text)) {
            $this->handleError("*Неверный формат даты*");
            return $this->bot->executeCommand(BirthCommand::$command);
        }

        if (iconv_strlen($text) > 31){
            $this->handleError("*Слишком много символов*");
            return $this->bot->executeCommand(BirthCommand::$command);
        }

        $this->getConversation()->update([
            'birth' => $text
        ]);
        
        return $this->bot->executeCommand(StartDate::$command);
    }
}
