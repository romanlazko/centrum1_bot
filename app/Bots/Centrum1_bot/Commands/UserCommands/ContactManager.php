<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\BuyInsurance;
use App\Bots\Centrum1_bot\Commands\UserCommands\Profile\Profile;
use App\Events\ChatWantsToContactManager;
use App\Models\Tag;
use App\Models\TelegramChatTag;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class ContactManager extends Command
{
    public static $command = 'contact_manager';

    public static $title = [
        'ru' => 'СВЯЗАТЬСЯ С МЕНЕДЖЕРОМ',
        'en' => 'CONTACT MANAGER',
    ];

    public static $usage = ['contact_manager'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $telegram_chat->update([
            'is_communicated' => false
        ]);

        event(new ChatWantsToContactManager($telegram_chat->id));

        $result = $this->bot->executeCommand(DataIsSend::$command);

        if ($result->getOk()) {
            return $this->bot->executeCommand(SendContact::$command);
        }
    }
}