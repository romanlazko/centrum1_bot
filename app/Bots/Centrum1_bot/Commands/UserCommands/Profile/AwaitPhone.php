<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\Profile;

use App\Bots\Centrum1_bot\Commands\UserCommands\Profile\Profile;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitPhone extends Command
{
    public static $expectation = 'await_phone';

    public static $pattern = '/^await_phone$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $text = $updates->getMessage()?->getText();

        if (iconv_strlen($text) > 31){
            $this->handleError("*Слишком много символов*");
            return $this->bot->executeCommand(Phone::$command);
        }

        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $telegram_chat->update([
            'profile_phone' => $text
        ]);
        
        return $this->bot->executeCommand(Profile::$command);
    }
}
