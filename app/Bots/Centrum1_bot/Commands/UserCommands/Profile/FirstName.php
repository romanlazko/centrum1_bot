<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\Profile;

use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Jobs\SendQuestionnaireAfter3Hours;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class FirstName extends Command
{
    public static $command = 'first_name';

    public static $title = [
        'ru' => 'Имя',
        'en' => 'Name',
    ];

    public static $usage = ['first_name'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitFirstName::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "Напишите пожалуйста свое имя:"
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