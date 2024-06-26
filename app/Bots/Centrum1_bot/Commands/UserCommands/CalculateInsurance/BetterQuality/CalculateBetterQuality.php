<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\BetterQuality;

use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Models\Colonnade;
use App\Models\Maxima;
use App\Models\Slavia;
use App\Models\VZP;
use Carbon\Carbon;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class CalculateBetterQuality extends Command
{
    public static $command = 'calculate_bq';

    public static $title = [
        'ru' => 'Посчитать лучшее качество страховки',
        'en' => 'Calculate the best quality of insurance',
    ];

    public static $usage = ['calculate_bq'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $start_date = Carbon::parse($this->getConversation()->notes['start_date']);
        $end_date = Carbon::parse($this->getConversation()->notes['end_date']);
        $count_of_month = ceil($start_date->diffInMonths($end_date));

        $request = (object)[
            'count_of_month' => $count_of_month,
            'birth' => $this->getConversation()->notes['birth'],
            'type' => $updates->getInlineData()->getType(),
            'shengen' => $updates->getInlineData()->getShengen() == '1' ? true : false
        ];
    
        $insurance = VZP::filterByRequest($request)->sortBy('price')->first();

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