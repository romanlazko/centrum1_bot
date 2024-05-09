<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands;

use App\Bots\Centrum1_bot\Commands\UserCommands\Profile\Profile;
use App\Models\Tag;
use App\Models\TelegramChatTag;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AssignTag extends Command
{
    public static $command = 'assign_tag';

    public static $title = [
    ];

    public static $usage = ['assign_tag'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        Tag::firstOrCreate(['name' => $updates->getInlineData()->getTemp()])
            ->chats()
            ->attach($telegram_chat->id);

        return $this->bot->executeCommand(DataIsSend::$command);
    }
}