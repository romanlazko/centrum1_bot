<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\PriceAndQuality;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\AwaitBirth;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateInsurance;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class PriceAndQuality extends Command
{
    public static $command = 'price_and_quality';

    public static $title = [
        'ru' => 'ЦЕНА И КАЧЕСТВО',
        'en' => 'PRICE AND QUALITY',
    ];

    public static $usage = ['price_and_quality'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitBirth::$expectation);

        $this->getConversation()->update([
            'criterium' => 'price_and_quality'
        ]);

        $text = implode("\n", [
            "Супер, мы подберем для вас самую выгодную страховку."."\n",
            "Давайте посчитаем, сколько такая страховка будет стоить для вас🧮",
            "Для этого нам нужно узнать ваш возраст."."\n",
            "*Напишите пожалуйста свою дату рождения в формате ДД.ММ.ГГГГ:*"
        ]);

        $buttons = BotApi::inlineKeyboard([
            [array("⬅️ НАЗАД", CalculateInsurance::$command, '')],
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