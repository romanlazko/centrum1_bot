<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Work;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IveBeenFired extends Command
{
    public static $command = 'ivebeenfired';

    public static $title = [
        'ru' => 'Меня уволили, что делать?',
        'en' => 'What to do if I am fired?',
    ];

    public static $usage = ['ivebeenfired'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'ivebeenfired')],
            [array("👈 НАЗАД", Work::$command, '')],
        ], 'type');

        $text = implode("\n\n", [
            "Если вас уволили, важно своевременно предпринять действия для сохранения визы, так как для легального пребывания в Чехии необходимо иметь актуальную цель нахождения.",
            "Мы проанализируем вашу ситуацию, предложим возможные варианты и поможем подготовить необходимые документы, чтобы избежать проблем с иммиграционными службами.",
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