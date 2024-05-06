<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\Profile;

use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitLastName extends Command
{
    public static $expectation = 'await_last_name';

    public static $pattern = '/^await_last_name$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $text = $updates->getMessage()?->getText();

        if (iconv_strlen($text) > 31){
            $this->handleError("*Слишком много символов*");
            return $this->bot->executeCommand(LastName::$command);
        }

        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $telegram_chat->update([
            'profile_last_name' => $text
        ]);
        
        return $this->bot->executeCommand(Profile::$command);
    }
}
