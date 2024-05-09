<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands;

use App\Bots\Centrum1_bot\Commands\UserCommands\CalculateInsurance\BuyInsurance;
use App\Bots\Centrum1_bot\Commands\UserCommands\Profile\Profile;
use App\Models\Tag;
use App\Models\TelegramChatTag;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class DataIsSend extends Command
{
    public static $command = 'data_is_send';

    public static $title = [
        'ru' => 'ДАННЫЕ ОТПРАВЛЕНЫ',
        'en' => 'Data is send',
    ];

    public static $usage = ['data_is_send'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array("ОСТАВИТЬ КОНТАКТНЫЕ ДАННЫЕ", Profile::$command, '')],
            [array("НАШИ КОНТАКТЫ", HelpCommand::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "Спасибо большое, Ваш запрос отправлен и будет обработан в ближайшее время."."\n",

            "Чтобы менеджеры могли идентифицировать ваш запрос, пожалуйста оставьте контактные данные, по которым они смогут связаться с вами и помочь⬇"
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
}