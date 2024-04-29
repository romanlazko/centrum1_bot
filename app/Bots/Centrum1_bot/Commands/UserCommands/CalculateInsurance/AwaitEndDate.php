<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use Carbon\Carbon;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitEndDate extends Command
{
    public static $expectation = 'await_end_date';

    public static $pattern = '/^await_end_date$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $text = $updates->getMessage()?->getText();

        if ($text === null OR !preg_match('/^(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[0-2])\.\d{4}$/', $text)) {
            $this->handleError("*Неверный формат даты*");
            return $this->bot->executeCommand(EndDate::$command);
        }

        if (iconv_strlen($text) > 31){
            $this->handleError("*Слишком много символов*");
            return $this->bot->executeCommand(EndDate::$command);
        }

        $start_date = Carbon::parse($this->getConversation()->notes['start_date']);

        if ($start_date > Carbon::parse($text)) {
            $this->handleError("*Дата окончания не может быть раньше даты начала*");
            return $this->bot->executeCommand(EndDate::$command);
        }

        $this->getConversation()->update([
            'end_date' => $text
        ]);
        
        return $this->bot->executeCommand(Shengen::$command);
    }
}
