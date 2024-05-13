<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\AfterUniversityVisa;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IWantAfterUniversityVisa extends Command
{
    public static $command = 'iwantafteruniversityvisa';

    public static $title = [
        'ru' => 'Хочу получить 9-ти месячную визу',
        'en' => 'I want to get a 9-month visa',
    ];

    public static $usage = ['iwantafteruniversityvisa'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'iwantafteruniversityvisa')],
            [array("👈 НАЗАД", AfterUniversityVisa::$command, '')],
        ], 'type');

        $text = implode("\n\n", [
            "Для получения визы после окончания вуза с целью поиска работы или начала ИП, необходимо подать заявление в МВД после окончания учебы.",
            "Виза выдается на срок до 9 месяцев и позволяет находиться в стране для поиска работы или регистрации бизнеса, а также в момент ее действия работать без дополнительных разрешений.",
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