<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\BetterQuality\BetterQuality;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\ContinuingTreatment\ContinuingTreatment;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\LowestCost\LowestCost;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\PriceAndQuality\PriceAndQuality;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Events\ChatStartCalculatingInsurance;
use App\Models\Tag;
use App\Models\TelegramChatTag;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Models\TelegramChat;

class CalculateInsuranceAgain extends Command
{
    public static $command = 'ci_again';

    public static $title = [
        'ru' => 'Повторный подсчет страховки',
        'en' => 'Calculate Insurance Again',
    ];

    public static $usage = ['ci_again'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $tag = Tag::where('name', '#повторный подсчет страховки')->first();

        if (!$tag) {
            $tag = Tag::create([
                'name' => '#повторный подсчет страховки'
            ]);
        }

        TelegramChatTag::create([
            'telegram_chat_id' => $telegram_chat->id,
            'tag_id' => $tag->id,
        ]);

        return $this->bot->executeCommand(CalculateInsurance::$command);
    }
}