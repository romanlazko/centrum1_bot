<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateBank\CalculateAmount;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\BirthCommand;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateInsurance;
use App\Bots\Centrum1_bot\Commands\UserCommands\Consulting\Consulting;
use App\Bots\Centrum1_bot\Commands\UserCommands\PMJ\PMJ;
use App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\VisaAndResidentPermit;
use App\Jobs\SendQuestionnaire;
use App\Jobs\SendQuestionnaireAfter3Hours;
use App\Models\Questionnaire\Questionnaire;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class MenuCommand extends Command
{
    public static $command = '/menu';

    public static $title = [
        'ru' => '🏠 ГЛАВНОЕ МЕНЮ',
        'en' => '🏠 MENU'
    ];

    public static $usage = ['/menu', 'menu', 'Главное меню', 'Menu'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->clear();
        $updates->getInlineData()->unset();

        $buttons = BotApi::inlineKeyboard([
            [array("📈 ПОДОБРАТЬ СТРАХОВКУ", CalculateInsurance::$command, '')],
            [array(CalculateAmount::getTitle('ru'), CalculateAmount::$command, '')],
            [array(VisaAndResidentPermit::getTitle('ru'), VisaAndResidentPermit::$command, '')],
            [array(Adress::getTitle('ru'), Adress::$command, '')],
            [array(PMJ::getTitle('ru'), PMJ::$command, '')],
            [array(Citizenship::getTitle('ru'), Citizenship::$command, '')],
            [array(MyApplicationTakingLongTimeToProcess::getTitle('ru'), MyApplicationTakingLongTimeToProcess::$command, '')],
            [array("☎️ НАШИ КОНТАКТЫ", HelpCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "Здравствуйте!😊"."\n",
            "Я — виртуальный помощниĸ Центра поддержĸи и страхования иностранцев в Чехии🇨🇿",
            "Здесь вы можете узнать ĸаĸ мы можем помочь в вашей ситуации: от оформления виз и продления статуса до переезда родственниĸов и подготовĸи доĸументов."."\n",
            "Выберите интересующую тему ниже ⬇",
        ]);

        $data = [
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        // $questionnaire = Questionnaire::where('is_active', true)->first();

        // SendQuestionnaire::dispatch($questionnaire?->id, $updates->getChat()->getId());

        return BotApi::returnInline($data);
    }
}