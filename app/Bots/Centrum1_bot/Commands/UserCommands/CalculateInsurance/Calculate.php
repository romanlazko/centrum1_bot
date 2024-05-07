<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use App\Bots\Centrum1_bot\Commands\UserCommands\ContactManager;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Jobs\SendQuestionnaire;
use App\Jobs\SendQuestionnaireAfter3Hours;
use App\Models\Colonnade;
use App\Models\Maxima;
use App\Models\Questionnaire\Questionnaire;
use App\Models\Slavia;
use App\Models\SV;
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
        BotApi::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => '*Подождите, идет поиск...*',
            'parse_mode' => 'Markdown',
            'message_id' => $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);

        $start_date = Carbon::parse($this->getConversation()->notes['start_date']);
        $end_date = Carbon::parse($this->getConversation()->notes['end_date']);
        $count_of_month = ceil($start_date->diffInMonths($end_date));

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
            "Вам подходит страховка: *{$insurance->type}*\n",
            "Её цена для вас составит на ". $count_of_month . " месяцев - " . $insurance->price . " крон!"."\n",
            "Обратите внимание, что в страховку ". ($updates->getInlineData()->getShengen() == '1' ? "*включено покрытие зоны Шенген*" : "*не включено покрытие зоны Шенген*"),
        ]);

        $buttons = BotApi::inlineKeyboardWithLink(
            array('text' => "ОФОРМИТЬ СТРАХОВКУ", 'web_app' => ['url' => 'https://forms.amocrm.ru/rvcmwdc']),
            [
                [array(InsuranceInfo::getTitle('ru'), InsuranceInfo::$command, $insurance->name)],
                [array(ContactManager::getTitle('ru'), ContactManager::$command, '')],
                [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
            ],
            'insurance_name'
        );

        $data = [
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        $questionnaire = Questionnaire::where('is_active', true)->where('service', 'insurance')->first();

        SendQuestionnaire::dispatch($questionnaire?->id, $updates->getChat()->getId())->delay(now()->addMinutes(1));

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