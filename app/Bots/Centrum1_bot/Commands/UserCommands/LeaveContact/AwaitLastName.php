<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact;

use Illuminate\Support\Facades\Validator;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitLastName extends Command
{
    public static $expectation = 'l_await_last_name';

    public static $pattern = '/^l_await_last_name$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $validator = Validator::make(
            ['last_name' => $updates->getMessage()?->getText()], 
            ['last_name' => 'required|string|max:36'], 
            [
                'last_name.required' => 'Поле имя обязательно к заполнению',
                'last_name.max' => 'Слишком много символов',
                'last_name.string' => 'Не верный формат имени',
            ]
        );

        if ($validator->stopOnFirstFailure()->fails()) {
            $this->handleError($validator->errors()->first());
            return $this->bot->executeCommand(LastName::$command);
        }

        $validated = $validator->validated();

        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $telegram_chat->update([
            'profile_last_name' => $validated['last_name'],
        ]);
        
        return $this->bot->executeCommand(LeaveContact::$command);
    }
}
