<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact;

use Illuminate\Support\Facades\Validator;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitPhone extends Command
{
    public static $expectation = 'l_await_phone';

    public static $pattern = '/^l_await_phone$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $validator = Validator::make(
            ['phone' => $updates->getMessage()?->getText()], 
            ['phone' => 'required|max:16|regex:/^(\+?\d{3}\s*)?\d{3}[\s-]?\d{3}[\s-]?\d{3}$/'], 
            [
                'phone.required' => 'Поле телефона обязательно к заполнению',
                'phone.max' => 'Слишком длинный номер телефона',
                'phone.regex' => 'Не верный формат номера телефона',
            ]
        );

        if ($validator->stopOnFirstFailure()->fails()) {
            $this->handleError($validator->errors()->first());
            return $this->bot->executeCommand(Phone::$command);
        }

        $validated = $validator->validated();

        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $telegram_chat->update([
            'profile_phone' => $validated['phone'],
        ]);
        
        return $this->bot->executeCommand(LeaveContact::$command);
    }
}
