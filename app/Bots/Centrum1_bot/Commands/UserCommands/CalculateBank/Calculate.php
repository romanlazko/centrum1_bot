<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateBank;

use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Bots\Centrum1_bot\Config;
use App\Jobs\SendQuestionnaire;
use App\Models\Questionnaire\Questionnaire;
use Carbon\Carbon;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Commands\UserCommands\AdvertisementCommand;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;

class Calculate extends Command
{
    public static $command = 'calculate_bank';

    public static $usage = ['calculate_bank'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $end_of_visa    = Carbon::parse($updates->getInlineData()->getEndOfVisa())->firstOfMonth();
        $deadline       = Carbon::parse($updates->getInlineData()->getDeadline())->firstOfMonth();
        $diffInMonths   = $end_of_visa->diff($deadline)->m;
        $applying 		= $updates->getInlineData()->getApplying();
        $amount         = 3130;

        if ($end_of_visa->lt($deadline)) {
            return BotApi::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text'              => "Подача на визу не может быть позже чем ее окончание.",
                'show_alert'        => true
            ]);
        }

        $calc        	= (15 * $amount) + (2 * $amount * ($diffInMonths + $applying - 1));
        $more_calc      = $calc + (2 * $amount * 2);

        $buttons = BotApi::inlineKeyboardWithLink(
            [
                'text' => 'ЗАПИСАТЬСЯ НА ОФОРМЛЕНИЕ ПОДТВЕРЖДЕНИЯ',
                'url' => 'https://t.me/bankercz_bot'
            ],
            [
                [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
            ]
        );

        $text = implode("\n", [
            "Сумма, которая Вам нужна для подачи: *{$calc} крон*."."\n",
            "В последнее время нам поступают жалобы, что подтверждение с указанной суммой МВД ЧР не принимает."."\n",
            "*Бот считает сумму правильно, в соответствии с законом.*"."\n",
            "Мы не хотим вводить в заблуждение пользователей и не хотим указывать сумму больше, однако рекомендуем позаботиться о том, что бы сумма на Вашем счету была больше, хотя бы: *{$more_calc} крон*."."\n",
            "Если Вам нужна помощь в оформлении подтверждения, Вы можете обращаться к нашим партнерам.",
        ]);

        try {
            return BotApi::returnInline([
                'text'          =>  $text,
                'chat_id'       =>  $updates->getChat()->getId(),
                'reply_markup'  =>  $buttons,
                'parse_mode'    =>  'Markdown',
                'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
            ]);
        }
        catch(TelegramException $e){
            return BotApi::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text'              => "Не торопись ".$e->getMessage(),
                'show_alert'        => true
            ]);
        }
    }
}
