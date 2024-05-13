<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact;

use Illuminate\Support\Facades\Validator;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitFirstName extends Command
{
    public static $expectation = 'l_await_first_name';

    public static $pattern = '/^l_await_first_name$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $validator = Validator::make(
            ['first_name' => $updates->getMessage()?->getText()], 
            ['first_name' => 'required|string|max:36'], 
            [
                'first_name.required' => 'Поле имя обязательно к заполнению',
                'first_name.max' => 'Слишком много символов',
                'first_name.string' => 'Не верный формат имени',
            ]
        );

        if ($validator->stopOnFirstFailure()->fails()) {
            $this->handleError($validator->errors()->first());
            return $this->bot->executeCommand(FirstName::$command);
        }

        $validated = $validator->validated();

        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $telegram_chat->update([
            'profile_first_name' => $validated['first_name'],
        ]);
        
        return $this->bot->executeCommand(LeaveContact::$command);
    }
}
