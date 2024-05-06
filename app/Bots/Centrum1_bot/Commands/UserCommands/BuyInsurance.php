<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands;

use App\Jobs\SendQuestionnaireAfter3Hours;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class BuyInsurance extends Command
{
    public static $command = 'buy_insurance';

    public static $title = [
        'ru' => 'ОФОРМИТЬ СТРАХОВКУ',
        'en' => 'BUY INSURANCE',
    ];

    public static $usage = ['buy_insurance'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboardWithLink(
            array('text' => "Заполнить форму", 'web_app' => ['url' => 'https://forms.amocrm.ru/rvcmwdc']),
            [
                [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
            ],
        );

        $text = implode("\n", [
            "Для оформления страховки перейдите пожалуйста по этой ссылке и заполните форму!👩‍💻"
        ]);

        $data = [
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        SendQuestionnaireAfter3Hours::dispatch($updates->getChat()->getId())->delay(now()->addHours(3));

        return BotApi::sendMessage($data);
    }
}