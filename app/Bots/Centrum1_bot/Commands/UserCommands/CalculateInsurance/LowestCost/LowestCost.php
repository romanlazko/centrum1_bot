<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\LowestCost;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\AwaitBirth;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateInsurance;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\SaveBirth;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class LowestCost extends Command
{
    public static $command = 'lowest_cost';

    public static $title = [
        'ru' => 'САМАЯ ДЕШЁВАЯ',
        'en' => 'BETTER PRICE'
    ];

    public static $usage = ['lowest_cost'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $updates->getFrom()->setExpectation(AwaitBirth::$expectation);

        $this->getConversation()->update([
            'criterium' => 'lowest_cost'
        ]);

        $text = implode("\n", [
            "Супер, цена это безусловно важный фактор, тем более, что даже самая дешевая страховка будет отвечать всем требованиям закона, гарантирует вам лечение и подходит для продления визы."."\n",
            "Давайте посчитаем, сколько такая страховка будет стоить для вас🧮",
            "Для этого нам нужно узнать ваш возраст."."\n",
            "*Напишите пожалуйста свою дату рождения в формате ДД.ММ.ГГГГ:*"
        ]);

        $buttons = BotApi::inlineKeyboard([
            $telegram_chat->profile_birth ? [array("Использовать: ".$telegram_chat->profile_birth, SaveBirth::$command, '')] : [],
            [array("👈 НАЗАД", CalculateInsurance::$command, '')],
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