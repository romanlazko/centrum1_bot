<?php 

namespace App\Bots\strachovanie1_bot\Commands\UserCommands\CalculateInsurance\ContinuingTreatment;

use App\Bots\strachovanie1_bot\Commands\UserCommands\CalculateInsurance\AwaitBirth;
use App\Bots\strachovanie1_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class ContinuingTreatment extends Command
{
    public static $command = 'continuing_treatment';

    public static $title = [
        'ru' => 'АКТУАЛЬНО ЛЕЧУСЬ',
        'en' => 'CONTINUING TREATMENT',
    ];

    public static $usage = ['continuing_treatment'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitBirth::$expectation);

        $this->getConversation()->update([
            'criterium' => 'continuing_treatment'
        ]);

        $text = implode("\n", [
            "(Тут текст)"."\n",
            "Выберите страховку для продолжения лечения",
        ]);

        $buttons = BotApi::inlineKeyboard([
            [array("СВЯЗАТЬСЯ С МЕНЕДЖЕРОМ", MenuCommand::$command, '')],
            [array("Maxima", SaveInsurance::$command, 'maxima')],
            [array("pVZP", SaveInsurance::$command, 'pvzp')],
            [array("Slavia", SaveInsurance::$command, 'slavia')],
            [array("Colonade", SaveInsurance::$command, 'colonade')],

            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ], 'current_insurance');

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