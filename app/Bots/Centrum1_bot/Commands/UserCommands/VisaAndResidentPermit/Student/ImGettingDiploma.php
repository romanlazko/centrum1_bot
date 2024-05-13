<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\Student;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class ImGettingDiploma extends Command
{
    public static $command = 'imgettingdiploma';

    public static $title = [
        'ru' => 'Скоро получаю диплом, что дальше делать?',
        'en' => 'I am getting a diploma, what next?',
    ];

    public static $usage = ['imgettingdiploma'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'imgettingdiploma')],
            [array("👈 НАЗАД", Student::$command, '')],
        ], 'type');

        $text = implode("\n", [
            "После окончания университета в Чехии вы обязаны подать заявление на новую визу, даже если текущая виза все еще действительна."."\n",
            "Это необходимо, чтобы ваше пребывание соответствовало целям, предусмотренным законом. 9-месячная виза дает вам время для поиска работы, открытия бизнеса или принятия решения о дальнейших планах. Мы поможем вам разобраться в процессе и спланировать следующие шаги для успешного продолжения пребывания в Чехии."."\n",
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