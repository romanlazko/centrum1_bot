<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Work;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IWantWorkVisa extends Command
{
    public static $command = 'iwantworkvisa';

    public static $title = [
        'ru' => 'Хочу получить рабочую визу',
        'en' => 'I want a work visa',
    ];

    public static $usage = ['iwantworkvisa'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'iwantworkvisa')],
            [array("👈 НАЗАД", Work::$command, '')],
        ], 'type');

        $text = implode("\n\n", [
            "Одним из ключевых моментов при получении рабочей визы в Чехии является правильное составление рабочего договора, который должен соответствовать требованиям чешского законодательства.",
            "Также важно, чтобы вакансия, на которую вы претендуете, была зарегистрирована на чешском рынке труда, что подтверждает потребность в вашей квалификации.",
            "Мы поможем подготовить правильно все необходимые документы, проверим рабочий договор с вакансией и в случае необходимости будем держать связь с вашим работодателем.",
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