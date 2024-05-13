<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Business;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IWantExtendMyBusinessVisa extends Command
{
    public static $command = 'iwantextendmybusinessvisa';

    public static $title = [
        'ru' => 'Хочу продлить предпринимательскую визу',
        'en' => 'I want to extend my business visa',
    ];

    public static $usage = ['iwantextendmybusinessvisa'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'iwantextendmybusinessvisa')],
            [array("👈 НАЗАД", Business::$command, '')],
        ], 'type');

        $text = implode("\n\n", [
            "Продление предпринимательской визы в Чехии зависит от успешности бизнеса и соблюдения всех обязательств.",
            "Заявку подают в МВД  не ранее 120 дней до окончания текущего разрешения и до последнего дня действия визы.",
            "Необходимо подтвердить стабильный доход, покрывающий все расходы, уплату налогов и социальных взносов, наличие жилья и медицинской страховки.",
            "Рассмотрение занимает до 60 дней, но во время ожидания можно продолжать работать.",
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