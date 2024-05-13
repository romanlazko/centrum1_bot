<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands;

use App\Bots\Centrum1_bot\Commands\UserCommands\LeaveContact\LeaveContact;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Citizenship extends Command
{
    public static $command = 'citizenship';

    public static $title = [
        'ru' => '🇨🇿 ГРАЖДАНСТВО',
        'en' => '🇨🇿 CITIZENSHIP'
    ];

    public static $usage = ['citizenship'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'theme' => static::getTitle('ru'),
        ]);
        
        $buttons = BotApi::inlineKeyboard([
            [array('👩‍💻 МНЕ НУЖНА КОНСУЛЬТАЦИЯ', LeaveContact::$command, 'citizenship')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "Получить гражданство Чехии можно после 10 лет пребывания в стране или 5 лет по ПМЖ.",
            "В некоторых случаях возможно подать заявление раньше.",
            "Требования включают стабильный доход, знание чешского языка, интеграцию в общество и соблюдение законов Чехии.",
            "Также необходимо доказать финансовую независимость и наличие постоянного места жительства.",
            "При одобрении заявитель получает чешский паспорт, который предоставляет права гражданина ЕС."."\n",
            "Наши специалисты могут вам помочь с:",
            "✅комплексная консультация по получению гражданства, учитывая вашу визовую историю",
            "✅собрать необходимый пакет документов",
            "✅составление мотивационного письма (резюме)",
            "✅ подача жалобы на задержку рассмотрения гражданства для ускорения процесса",
            "✅полное ведение дела под ключ"."\n",
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