<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\ContinuingTreatment;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\AwaitBirth;
use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\CalculateInsurance;
use App\Bots\Centrum1_bot\Commands\UserCommands\ContactManager;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
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
            "В случае если вы проходите актуально лечение или у вас есть вероятность необходимости дальнейшего лечения по старому заболеванию - *мы рекомендуем* продление вашей действующей страховки, для избежания проблем с покрытием затрат на дальнейшее лечение."."\n",

            "Давайте посчитаем, сколько будет стоить продлить вашу страховку, *для этого выберите свою компанию⬇*",
        ]);

        $buttons = BotApi::inlineKeyboard([
            [array(ContactManager::getTitle('ru'), ContactManager::$command, '')],
            [array("MAXIMA", SaveInsurance::$command, 'maxima')],
            [array("PVZP", SaveInsurance::$command, 'pvzp')],
            [array("SLAVIA", SaveInsurance::$command, 'slavia')],
            [array("COLONADE", SaveInsurance::$command, 'colonade')],
            [array("SV (EX. ERGO)", SaveInsurance::$command, 'sv')],
            [array("⬅️ НАЗАД", CalculateInsurance::$command, '')],
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