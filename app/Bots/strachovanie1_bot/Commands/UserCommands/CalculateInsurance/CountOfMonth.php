<?php 

namespace App\Bots\strachovanie1_bot\Commands\UserCommands\CalculateInsurance;

use App\Bots\strachovanie1_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class CountOfMonth extends Command
{
    public static $command = 'count_of_month';

    public static $title = [
        'ru' => 'Количество месяцев',
        'en' => 'Count of month',
    ];

    public static $usage = ['count_of_month'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $counter = $this->Counter($updates->getInlineData()->getCountOfMonth(), Shengen::$command, 3, 24);

        $buttons = BotApi::inlineKeyboard([
            $counter,
            [array('9', Shengen::$command, '9')],
            [array('6', Shengen::$command, '6')],
            [array('3', Shengen::$command, '3')],
            [
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, ''),
            ]
        ], 'count_of_month');

        $text = implode("\n", [
            "Супер, наш бот уже подобрал для вас лучшие варианты, осталось всего несколько шажочков."."\n",
            "*Теперь укажите на сколько месяцев вам нужна новая страховка:*"
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

    public function Counter(?string $count, $command, int $min_count = 0, int $max_count = 100)
	{
		$count 		= $count ?? $min_count;

		$counter = [
			array('<', 						'count_of_month', 		$count > $min_count ? $count-1 : $min_count), 
			array($count, 					$command, 		$count), 
			array('>', 						'count_of_month', 		$count < $max_count ? $count+1 : $max_count)
		];

		return $counter;
	}
}