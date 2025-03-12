<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\VisaAndResidentPermit\LanguageCourses;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class LanguageCourses2324 extends Command
{
    public static $command = 'languagecourses2324';

    public static $title = [
        'ru' => 'Языковые курсы по 23/24 типу пребывания',
        'en' => 'Language courses for 23/24 type of stay',
    ];

    public static $usage = ['languagecourses2324'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array(LeaveContact::getTitle('ru'), LeaveContact::$command, 'languagecourses2324')],
            [array("👈 НАЗАД", LanguageCourses::$command, '')],
        ], 'type');

        $text = implode("\n", [
            "Курс при гос. ВУЗе VŠCHT"."\n",
            "Специализированный курс чешского языка при гос. ВУЗе VŠCHT. Обучение основам, грамматике, увеличение словарного запаса и разговорная практика с носителем языка."."\n",
            "Обучение до уровня B2, по окончанию курса сертификат общеевропейского образца CERR. Визовая поддержка на протяжении всего курса. Код визы 23/24. Город обучения Брно и Прага."."\n",
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