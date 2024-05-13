<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\LanguageCourses;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class LanguageCourses99 extends Command
{
    public static $command = 'languagecourses99';

    public static $title = [
        'ru' => 'Языковые курсы по 99 типу пребывания',
        'en' => 'Language courses for 99 type of stay',
    ];

    public static $usage = ['languagecourses99'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'languagecourses99')],
            [array("👈 НАЗАД", LanguageCourses::$command, '')],
        ], 'type');

        $text = implode("\n", [
            "Курс при языковой школе"."\n",
            "Универсальный курс чешского языка, обучение основам, грамматике, увеличение словарного запаса и разговорная практика с носителем языка."."\n",
            "Обучение возможно до уровня В2, по окончанию курса сертификат общеевропейского образца CERR."."\n",
            "Визовая поддержка на протяжении всего курса, полноценный стаж для ПМЖ (год за год). Код визы 99. Город обучения Брно и Прага."."\n",
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