<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Family;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IWantTemporaryResidence extends Command
{
    public static $command = 'iwanttemporaryresidence';

    public static $title = [
        'ru' => 'Хочу получить přechodný pobyt',
        'en' => 'I want to get a visa for a temporary residence',
    ];

    public static $usage = ['iwanttemporaryresidence'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'iwanttemporaryresidence')],
            [array("👈 НАЗАД", Family::$command, '')],
        ], 'type');

        $text = implode("\n\n", [
            "Заявление на přechodný pobyt могут подать близкие родственники гражданина ЕС, если планируют находиться в Чехии более 90 дней.",
            "Родственники делятся на близких (например, супруги, дети, родители) и дальних (например, партнеры, экономически зависимые члены семьи).",
            "Этот статус подтверждает легальное проживание в Чехии и дает право на работу без дополнительных разрешений.",
            "Заявление рассматривается МВД в течение нескольких месяцев, после чего выдается карта.",
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