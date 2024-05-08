<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\BetterQuality\BetterQuality;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\ContinuingTreatment\ContinuingTreatment;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\LowestCost\LowestCost;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\PriceAndQuality\PriceAndQuality;
use App\Bots\Centrum1_bot\Commands\UserCommands\ContactManager;
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

class OppositionInsurance extends Command
{
    public static $command = 'opposition';

    public static $title = [
        'ru' => 'Меня не устраивает ваше предложение',
        'en' => 'I do not agree with your offer',
    ];

    public static $usage = ['opposition'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $tag = Tag::where('name', '#возражение')->first();

        if (!$tag) {
            $tag = Tag::create([
                'name' => '#возражение'
            ]);
        }

        TelegramChatTag::create([
            'telegram_chat_id' => $telegram_chat->id,
            'tag_id' => $tag->id,
        ]);

        $buttons = BotApi::inlineKeyboard([
            [array('Да, хочу индивидуальное предложение', ContactManager::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "Мы ценим вашу обратную связь и хотели бы предложить вам тот продукт и цену, которые вас устроят!"."\n",
            "Может ли наш менеджер связаться с вами и сделать вам индивидуальное предложение?"
        ]);

        $data = [
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        return BotApi::returnInline($data);
    }
}