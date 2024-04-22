<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\BetterQuality\CalculateBetterQuality;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\ContinuingTreatment\CalculateContinuingTreatment;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\LowestCost\CalculateLowestCost;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\LowestCost\LowestCost;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\PriceAndQuality\CalculatePriceAndQuality;
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

class Calculate extends Command
{
    public static $command = 'calculate';

    public static $title = [
        'ru' => 'Посчитать страховку',
        'en' => 'Calculate Insurance'
    ];

    public static $usage = ['calculate'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $start_date = Carbon::parse($this->getConversation()->notes['start_date']);
        $end_date = Carbon::parse($this->getConversation()->notes['end_date']);
        $count_of_month = ceil($start_date->diffInMonths($end_date));

        // $request = (object)[
        //     'count_of_month' => $count_of_month,
        //     'birth' => $this->getConversation()->notes['birth'],
        //     'type' => $updates->getInlineData()->getType(),
        //     'shengen' => $updates->getInlineData()->getShengen() == '1' ? true : false,
        //     'start_date' => $start_date,
        // ];

        $data = (object)[
            'type' => $updates->getInlineData()->getType(),
            'shengen' => $updates->getInlineData()->getShengen() == '1' ? true : false,
            'start_date' => $this->getConversation()->notes['start_date'],
            'count_of_month' => $count_of_month,
            'birth' => $this->getConversation()->notes['birth'],
        ];

        $insurance = $this->getInsurance()($data);

        if ($insurance == null) {
            return $this->handleError('*Не удалось подобрать страховку с заданными параметрами*');
        }

        $text = implode("\n", [
            "Мы подобрали для вас самую подходящую страховку и более того, добавили к ней все существующие актульно скидки и бонусы, о которых вам расскажет менеджер при оформлении договора!"."\n",
            "Вам подходит страховка: *".($insurance->type ?? $insurance->insurance)."*\n",
            "Её цена для вас составит на ". $count_of_month . " месяцев - " . $insurance->price . " крон!"."\n",
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

    public function getInsurance()
    {
        return match ($this->getConversation()->notes['criterium']) {
            'lowest_cost' => function ($request) {
                $collection = collect([
                    Slavia::filterByRequest($request)->sortBy('price')->take(1),
                    Maxima::filterByRequest($request)->sortBy('price')->take(1),
                    Colonnade::filterByRequest($request)->sortBy('price')->take(1),
                    VZP::filterByRequest($request)->sortBy('price')->take(1),
                ]);
            
                return $collection->flatten()->sortBy('price')->first();
            },

            'price_and_quality' => function ($request) {
                return Maxima::filterByRequest($request)->sortBy('price')->first();
            },

            'continuing_treatment' => function ($request) {
                return match ($this->getConversation()->notes['current_insurance']) {
                    'pvzp' => VZP::filterByRequest($request)->sortBy('price')->first(),
                    'maxima' => Maxima::filterByRequest($request)->sortBy('price')->first(),
                    'slavia' => Slavia::filterByRequest($request)->sortBy('price')->first(),
                    'colonade' => Colonnade::filterByRequest($request)->sortBy('price')->first(),
                    // 'sv' => SV::filterByRequest($request)->sortBy('price')->first(),
                    default => null
                };
            },

            'better_quality' => function ($request) {
                return VZP::filterByRequest($request)->sortBy('price')->first();
            },

            default => null,
        };
    }

    // return match ($this->getConversation()->notes['criterium']) {
    //     'lowest_cost' => $this->bot->executeCommand(CalculateLowestCost::$command),
    //     'price_and_quality' => $this->bot->executeCommand(CalculatePriceAndQuality::$command),
    //     'continuing_treatment' => $this->bot->executeCommand(CalculateContinuingTreatment::$command),
    //     'better_quality' => $this->bot->executeCommand(CalculateBetterQuality::$command),
    //     default => $this->bot->executeCommand(MenuCommand::$command),
    // };
}