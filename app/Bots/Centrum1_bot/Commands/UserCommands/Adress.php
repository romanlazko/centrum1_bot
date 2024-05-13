<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Adress extends Command
{
    public static $command = 'adress';

    public static $title = [
        'ru' => '🏠 ЖИЛЬЕ/ЮР АДРЕС',
        'en' => '🏠 ADRESS'
    ];

    public static $usage = ['adress'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->clear();
        $updates->getInlineData()->unset();

        $buttons = BotApi::inlineKeyboardWithLink([
            'url'   =>  'https://taplink.cc/strachovanie1/p/ubytovani/',
            'text'  =>  '📤 Оформить прописку/юр. адрес'
        ],
        [
            [array('❓Есть вопрос, свяжитесь со мной', LeaveContact::$command, 'adress')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "🏠Подтверждения о жилье (прописка) заключается в резервации места в общежитии и выставлении необходимых документов регистрации в МВД (получение либо продление визы ).",
            "Оформляем для 2 городов: Брно и Прага."."\n",
            "📤Оформление юридического адреса — обязательное требование для регистрации ИП или фирмы в Чехии.",
            "Оформляем для 2 городов: Брно и Прага.",
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