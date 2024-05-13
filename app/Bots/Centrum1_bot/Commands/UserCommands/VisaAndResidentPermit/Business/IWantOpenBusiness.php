<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Business;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IWantOpenBusiness extends Command
{
    public static $command = 'iwantopenbusiness';

    public static $title = [
        'ru' => 'Хочу открыти ип/фирму',
        'en' => 'I want to open a business',
    ];

    public static $usage = ['iwantopenbusiness'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'iwantopenbusiness')],
            [array("👈 НАЗАД", Business::$command, '')],
        ], 'type');

        $text = implode("\n\n", [
            "Важно правильно разобраться во всех нюансах открытия фирмы или ИП, чтобы избежать ошибок.",
            "Мы подготовим необходимые документы, объясним ваши права и обязанности, в какую налоговую категорию вы будете относиться, как правильно выставить фактуры а также закрыть налоговый год.",
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