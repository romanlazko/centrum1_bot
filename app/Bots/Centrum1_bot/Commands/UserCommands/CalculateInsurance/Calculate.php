<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use App\Bots\Centrum1_bot\Commands\UserCommands\ContactManager;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Events\ChatFinishCalculatingInsurance;
use App\Jobs\SendNotificationToFinishOrderingInsurance;
use App\Models\Colonnade;
use App\Models\Maxima;
use App\Models\Slavia;
use App\Models\SV;
use App\Models\VZP;
use Carbon\Carbon;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Calculate extends Command
{
    public static $command = 'calculate';

    public static $title = [
        'ru' => 'ПОСЧИТАТЬ СТРАХОВКУ',
        'en' => 'CALCULATE INSURANCE'
    ];

    public static $usage = ['calculate'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        BotApi::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => '*Подождите, идет поиск...*',
            'parse_mode' => 'Markdown',
            'message_id' => $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);

        $shengen = $updates->getInlineData()->getShengen() == '1' ? true : false;

        // $start_date = Carbon::parse($this->getConversation()->notes['start_date']);
        // $end_date = Carbon::parse($this->getConversation()->notes['end_date']);
        // $count_of_month = ceil($start_date->diffInMonths($end_date));


        $count_of_month = $updates->getInlineData()->getApplying();

        $request = (object)[
            'type' => $updates->getInlineData()->getType(),
            'shengen' => $shengen,
            'start_date' => $this->getConversation()->notes['start_date'],
            'count_of_month' => $count_of_month,
            'birth' => $this->getConversation()->notes['birth'],
        ];

        $insurance = $this->getInsurance()($request);

        if ($insurance == null) {
            return $this->handleError('*Не удалось подобрать страховку с заданными параметрами*');
        }

        $shengen_text = "";

        if ($insurance->shengen AND $shengen) {
            $shengen_text = implode("\n", [
                "Обратите внимание в цену вашей страховки *входит покрытие зоны Шенген*.",
                "В случае краткосрочных поездок вам больше нет необходимости покупать дополнительную страховку👍"
            ]);
        }
        
        if ($insurance->shengen AND !$shengen) {
            $shengen_text = implode("\n", [
                "ОТЛИЧНАЯ НОВОСТЬ:",
                "На данный момент к вашему варианту страховки покрытие зоны Шенген идет в подарок - бесплатно🎁"
            ]);
        }

        $text = implode("\n", [
            "Мы подобрали для вас самую подходящую страховку и более того, добавили к ней все существующие актульно скидки и бонусы, о которых вам расскажет менеджер при оформлении договора!"."\n",
            "Вам подходит страховка: *{$insurance->type}*\n",
            "Её цена для вас составит на ". $count_of_month . " месяцев - " . $insurance->price . " крон!"."\n",
            $shengen_text
        ]);

        $buttons = BotApi::inlineKeyboard([
                [array(BuyInsurance::getTitle('ru'), BuyInsurance::$command, '')],
                [array(InsuranceInfo::getTitle('ru'), InsuranceInfo::$command, $insurance->name)],
                [array(ContactManager::getTitle('ru'), ContactManager::$command, '')],
                [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
            ], 'insurance_name');

        $data = [
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];


        $telegram_chat = DB::getChat($updates->getChat()->getId());

        event(new ChatFinishCalculatingInsurance($telegram_chat->id));

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
                    SV::filterByRequest($request)->sortBy('price')->take(1),
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
                    'sv' => SV::filterByRequest($request)->sortBy('price')->first(),
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