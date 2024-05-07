<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance;


use App\Events\ChatFinishCalculatingInsurance;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class OrderInsuranceNotifyLater extends Command
{
    public static $command = 'oin_later';

    public static $title = [
        'ru' => 'Напомнить позже',
        'en' => 'Notify later',
    ];

    public static $usage = ['oin_later'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $text = implode("\n", [
            "Хорошо мы напоним Вам позже об этом"
        ]);

        $data = [
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        $telegram_chat = DB::getChat($updates->getChat()->getId());

        event(new ChatFinishCalculatingInsurance($telegram_chat->id));
        
        return BotApi::returnInline($data);
    }
}