<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;

use App\Bots\Centrum1_bot\Commands\UserCommands\ContactManager;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class InsuranceInfo extends Command
{
    public static $command = 'insurance_info';

    public static $title = [
        'ru' => 'РАССКАЖИТЕ ПОДРОБНЕЕ О СТРАХОВКЕ',
        'en' => 'LEARN MORE ABOUT INSURANCE',
    ];

    public static $usage = ['insurance_info'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $name = $updates->getInlineData()->getName();

        $buttons = BotApi::inlineKeyboardWithLink(
            array('text' => "ОФОРМИТЬ СТРАХОВКУ", 'web_app' => ['url' => 'https://forms.amocrm.ru/rvcmwdc']),
            [
                [array(ContactManager::getTitle('ru'), ContactManager::$command, '')],
                [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
            ],
        );

        $data = [
            'text'          =>  $this->getInsuranceInfo($name),
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        return BotApi::returnInline($data);
    }

    public function getInsuranceInfo($name)
    {
        return match ($name) {
            'Maxima' => implode("\n", [
                "*Maxima*"."\n",
                "Лучшее сочетание стоимость/качество и условия для разных возрастных групп."."\n",
                "— более 140 договорных больниц и врачей по всей Чехии,",
                "— ежегодный осмотр у терапевта, гинеколога, уролога,",
                "— страхование гражданской ответственности в подарок к годовой страховке на сумму 1 млн. Kč (ущерб 3-им лицам по неосторожности застрахованного),",
                "— покрытие комплексного лечения на сумму 10 млн. чешских крон по Чехии,",
                "— покрытие экстренных случаев в зоне Шенген,",
                "— пакет MaxCare на сумму 3.000 крон (очки, линзы, необязательная вакцинация, слуховой аппарат, свободно доступные лекарства и медицинские препараты, приобретенные в аптеках (без рецепта),",
                "— 10 000 Kč на экстренное лечение зубов и общая стоматология.",
            ]),
            'pVZP' => implode("\n", [
                "*pVZP*"."\n",
                "Популярная страховка для иностранцев."."\n",
                "— широкий список больниц и врачей по всей Чехии,",
                "— покрытие комплексного лечения на сумму 10 млн. чешских крон по Чехии,",
                "— покрытие экстренных случаев в зоне Шенген,",
                "— пакет надстандарт на сумму 3.600 крон (очки, линзы, необязательная вакцинация, слуховой аппарат, свободно доступные лекарства и медицинские препараты, приобретенные в аптеках (без рецепта),",
                "— 10 000 Kč на экстренное лечение зубов и общая стоматология.",
            ]),
            'Slavia' => implode("\n", [
                "*Slavia*"."\n",
                "Базовый вариант страхования с покрытием комплексного обслуживания и получения долгосрочной визы/ВНЖ в Чехии."."\n",
                "— покрытие комплексного лечения на сумму 10 млн. чешских крон по Чехии,",
                "— покрытие экстренных случаев в зоне Шенген,",
                "— 15 000 Kč на экстренное лечение зубов и общая стоматология.",
            ]),
            'Colonnade' => implode("\n", [
                "Colonnade"."\n",
                "Новая страховка на рынке страхования для иностранцев. Базовый вариант страхования с покрытием комплексного обслуживания и получения долгосрочной визы/ВНЖ в Чехии."."\n",
                "— покрытие комплексного лечения на сумму 10 млн. чешских крон по Чехии,",
                "— покрытие экстренных случаев в зоне Шенген,",
                "— покрытие обязательной вакцинации на сумму 20.000 Kč.",
            ]),
            'SV' => implode("\n", [
                "*SV (ex. Ergo)*"."\n",
                "Базовый вариант страхования с покрытием комплексного обслуживания и получения долгосрочной визы/ВНЖ в Чехии."."\n",
                "— покрытие комплексного лечения на сумму 10 млн. чешских крон по Чехии,",
                "— покрытие экстренных случаев в зоне Шенген,",
                "— 6 000 Kč на экстренное лечение зубов и общая стоматология.",
            ]),
        };
    }
}