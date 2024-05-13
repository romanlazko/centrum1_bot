<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Work;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IWantChangeEmployer extends Command
{
    public static $command = 'iwantchangeemployer';

    public static $title = [
        'ru' => 'Хочу сменить работодателя',
        'en' => 'I want to change my employer',
    ];

    public static $usage = ['iwantchangeemployer'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'iwantchangeemployer')],
            [array("👈 НАЗАД", Work::$command, '')],
        ], 'type');

        $text = implode("\n\n", [
            "Смена работодателя возможна, но требует уведомления МВД. Новый работодатель должен предложить вакансию, соответствующую условиям текущей карты, после чего подается заявление на смену работы.",
            "Важно дождаться официального разрешения, прежде чем приступать к новым обязанностям, иначе можно нарушить визовый режим. В некоторых случаях смена проходит иначе.",
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