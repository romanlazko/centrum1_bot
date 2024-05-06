<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateBank\CalculateAmount;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\BirthCommand;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateInsurance;
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
        'ru' => '🏠 Главное меню',
        'en' => '🏠 Menu'
    ];

    public static $usage = ['/menu', 'menu', 'Главное меню', 'Menu'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->clear();
        $updates->getInlineData()->unset();

        $buttons = BotApi::inlineKeyboard([
            [array("ПОДОБРАТЬ СТРАХОВКУ", CalculateInsurance::$command, '')],
            [array(CalculateAmount::getTitle('ru'), CalculateAmount::$command, '')],
            [array("Контакты", HelpCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "Здравствуйте, мы рады, что вы решили воспользоваться нашим ботом и выбрать для себя не только самую выгодную, но и самую подходящую вам страховку, давайте начнем! 👆"
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