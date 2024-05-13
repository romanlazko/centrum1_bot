<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Student;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class IWantExtendMyStudyVisa extends Command
{
    public static $command = 'iwantextendmystudyvisa';

    public static $title = [
        'ru' => 'Хочу продлить студенческую визу',
        'en' => 'I want to extend my study visa',
    ];

    public static $usage = ['iwantextendmystudyvisa'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'iwantextendmystudyvisa')],
            [array("👈 НАЗАД", Student::$command, '')],
        ], 'type');

        $text = implode("\n", [
            "Продление студенческой визы необходимо оформить до истечения ее срока, заявление можно подать за 90 дней до окончания и до последнего дня действия визы."."\n",
            "Для продления требуется подтверждение продолжения учебы, документ о проживании, медицинская страховка и доказательство финансовой состоятельности."."\n",
            "Рассмотрение заявки может занять до 60 дней, поэтому важно подать документы заранее. При соблюдении всех условий продление обычно проходит без проблем. В случае отказа можно подать апелляцию."."\n",
            "*Нужна более подробная информация?*",
            "*Ниже оставьте, пожалуйста, ваши контакты, чтобы менеджер с вами связался ☎️*",
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