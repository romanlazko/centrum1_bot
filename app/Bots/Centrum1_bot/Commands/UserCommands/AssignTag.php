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

        $tag = Tag::where('name', $updates->getInlineData()->getTemp())->first();

        if (!$tag) {
            $tag = Tag::create([
                'name' => $updates->getInlineData()->getTemp()
            ]);
        }

        TelegramChatTag::create([
            'telegram_chat_id' => $telegram_chat->id,
            'tag_id' => $tag->id,
        ]);

        return $this->bot->executeCommand(Profile::$command);
    }
}