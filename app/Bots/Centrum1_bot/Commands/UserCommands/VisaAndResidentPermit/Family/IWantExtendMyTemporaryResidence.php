<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Family;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IWantExtendMyTemporaryResidence extends Command
{
    public static $command = 'iwantextendmytemporaryresidence';

    public static $title = [
        'ru' => 'Хочу продлить přechodný pobyt',
        'en' => 'I want to get a visa for a temporary residence',
    ];

    public static $usage = ['iwantextendmytemporaryresidence'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'iwantextendmytemporaryresidence')],
            [array("👈 НАЗАД", Family::$command, '')],
        ], 'type');

        $text = implode("\n\n", [
            "Продление přechodného pobytu для родственников граждан ЕС возможно, если сохраняются основания для пребывания в Чехии.",
            "Важно подтвердить, что семейные связи остаются актуальными, а условия проживания соответствуют требованиям.",
            "Рассмотрение заявки может занять несколько месяцев, но в этот период заявитель сохраняет право на проживание.",
            "Длительное пребывание с этим статусом может в дальнейшем дать возможность получить ПМЖ.",
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