<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Family;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IWantExtendMyFamilyVisa extends Command
{
    public static $command = 'iwantextendmyfamilyvisa';

    public static $title = [
        'ru' => 'Хочу продлить визу по воссоединению с семьей или партнерское пребывание',
        'en' => 'I want to extend my family visa or partner residence',
    ];

    public static $usage = ['iwantextendmyfamilyvisa'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'iwantextendmyfamilyvisa')],
            [array("👈 НАЗАД", Family::$command, '')],
        ], 'type');

        $text = implode("\n\n", [
            "Продление визы по воссоединению семьи или партнерству в Чехии начинается в МВД за 120 дней до окончания текущего разрешения и до последнего дня его действия.",
            "Важно подтвердить, что семейные или партнерские отношения продолжаются, а условия проживания остаются стабильными.",
            "Рассмотрение заявки может занять несколько месяцев, но на этот период сохраняется право на проживание.",
            "Длительное проживание по этой визе может в дальнейшем дать возможность получить ПМЖ.",
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