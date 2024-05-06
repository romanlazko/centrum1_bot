<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\Profile;

use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitFirstName extends Command
{
    public static $expectation = 'await_first_name';

    public static $pattern = '/^await_first_name$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $text = $updates->getMessage()?->getText();

        if (iconv_strlen($text) > 31){
            $this->handleError("*Слишком много символов*");
            return $this->bot->executeCommand(FirstName::$command);
        }

        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $telegram_chat->update([
            'profile_first_name' => $text
        ]);
        
        return $this->bot->executeCommand(Profile::$command);
    }
}
