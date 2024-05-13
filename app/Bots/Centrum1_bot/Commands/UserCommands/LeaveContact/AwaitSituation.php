<?php

namespace App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact;

use Illuminate\Support\Facades\Validator;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitSituation extends Command
{
    public static $expectation = 'l_await_situation';

    public static $pattern = '/^l_await_situation$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $validator = Validator::make(
            ['situation' => $updates->getMessage()?->getText()], 
            ['situation' => 'max:600'],
            [
                'situation.max' => 'Слишком много символов',
            ]
        );

        if ($validator->stopOnFirstFailure()->fails()) {
            $this->handleError($validator->errors()->first());
            return $this->bot->executeCommand(Situation::$command);
        }

        $validated = $validator->validated();

        $this->getConversation()->update([
            'situation' => $validated['situation'],
        ]);
        
        return $this->bot->executeCommand(LeaveContact::$command);
    }
}