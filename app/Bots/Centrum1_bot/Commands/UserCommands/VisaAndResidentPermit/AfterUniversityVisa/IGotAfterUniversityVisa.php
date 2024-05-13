<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\AfterUniversityVisa;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IGotAfterUniversityVisa extends Command
{
    public static $command = 'igotafteruniversityvisa';

    public static $title = [
        'ru' => 'Я получил 9-месячную визу, что дальше?',
        'en' => 'I got a 9-month visa, what next?',
    ];

    public static $usage = ['igotafteruniversityvisa'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'igotafteruniversityvisa')],
            [array("👈 НАЗАД", AfterUniversityVisa::$command, '')],
        ], 'type');

        $text = implode("\n\n", [
            "До окончания действия 9-месячной визы для поиска работы или начала ИП, важно заранее решить, на какой тип визы вы хотите податься, так как некоторые категории требуют длительного сбора документов.",
            "Например, для рабочей визы необходимо иметь трудовой договор, а для предпринимательской визы — успешную регистрацию бизнеса и доказательства стабильного дохода.",
            "Поэтому рекомендуется начать подготовку заранее, чтобы избежать проблем.",
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