<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Work;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IWantExtendMyWorkVisa extends Command
{
    public static $command = 'iwantextendmyworkvisa';

    public static $title = [
        'ru' => 'Нужно продлить рабочую визу',
        'en' => 'I want to extend my work visa',
    ];

    public static $usage = ['iwantextendmyworkvisa'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'iwantextendmyworkvisa')],
            [array("👈 НАЗАД", Work::$command, '')],
        ], 'type');

        $text = implode("\n\n", [
            "Продление рабочего пребывания можно подать за 120 дней до истечения ее срока и до окончания ее действия, подав заявление в местное МВД.",
            "Рассмотрение заявки может занять до 60 дней, но на время ожидания заявитель сохраняет право на работу.",
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