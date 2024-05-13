<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Business;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IWantBusinessVisa extends Command
{
    public static $command = 'iwantbusinessvisa';

    public static $title = [
        'ru' => 'Хочу получить предпринимательскую визу',
        'en' => 'I want a business visa',
    ];

    public static $usage = ['iwantbusinessvisa'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'iwantbusinessvisa')],
            [array("👈 НАЗАД", Business::$command, '')],
        ], 'type');

        $text = implode("\n\n", [
            "Получение визы на основании ИП может быть сложным и вызывать много вопросов.",
            "Вы должны соблюдать ряд условий: наличие действующего ип, нескольких клиентов-заказчиков, и определенный стаж пребывания в Чехии.",
            "Мы поможем вам пройти этот процесс уверенно: разберем все детали, подготовим план-получения визы, проверим документы для подачи, а также проконтролируем процесс рассмотрения заявки.",
            "Наша задача — сделать этот этап максимально простым и без стресса, чтобы вы могли сосредоточиться на развитии своего дела в Чехии.",
            "*Нужна более подробная консультация?*\n*Ниже оставьте, пожалуйста, ваши контакты, чтобы менеджер с вами связался ☎️*",
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