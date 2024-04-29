<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class DefaultCommand extends Command
{
    public static $command = '/default';

    public static $title = [
    ];

    public static $usage = ['/default', 'default'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        return BotApi::emptyResponse();
    }
}