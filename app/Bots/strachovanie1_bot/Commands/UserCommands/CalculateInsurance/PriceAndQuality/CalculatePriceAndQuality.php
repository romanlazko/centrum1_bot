<?php 

namespace App\Bots\strachovanie1_bot\Commands\UserCommands\CalculateInsurance\PriceAndQuality;

use App\Bots\strachovanie1_bot\Commands\UserCommands\MenuCommand;
use App\Models\Colonnade;
use App\Models\Maxima;
use App\Models\Slavia;
use App\Models\VZP;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class CalculatePriceAndQuality extends Command
{
    public static $command = 'calculate_paq';

    public static $title = [
        'ru' => 'Посчитать цену и качество страховки',
        'en' => 'Calculate price and quality of insurance',
    ];

    public static $usage = ['calculate_paq'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $request = (object)[
            'count_of_month' => $updates->getInlineData()->getCountOfMonth(),
            'birth' => $this->getConversation()->notes['birth'],
            'type' => $updates->getInlineData()->getType(),
            'shengen' => $updates->getInlineData()->getShengen() == '1' ? true : false
        ];
    
        $insurance = Maxima::filterByRequest($request)->sortBy('price')->first();

        if ($insurance == null) {
            return $this->handleError('*Не удалось подобрать страховку с заданными параметрами*');
        }

        $text = implode("\n", [
            "Мы подобрали для вас самую подходящую страховку и более того, добавили к ней все существующие актульно скидки и бонусы!"."\n",
            "Вам подходит страховка: *".($insurance->type ?? $insurance->insurance)."*\n",
            "Её цена для вас составит на ". $updates->getInlineData()->getCountOfMonth() . " месяцев - " . $insurance->price . " крон!"."\n",
            "Обратите внимание, что в страховку ". ($updates->getInlineData()->getShengen() == '1' ? "*включено покрытие зоны Шенген*" : "*не включено покрытие зоны Шенген*"),
        ]);

        $buttons = BotApi::inlineKeyboard([
            [array("РАССКАЖИТЕ ПОДРОБНЕЕ О СТРАХОВКЕ", MenuCommand::$command, '')],
            [array("ОФОРМИТЬ СТРАХОВКУ", MenuCommand::$command, '')],
            [array("СВЯЗАТЬСЯ С МЕНЕДЖЕРОМ", MenuCommand::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
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