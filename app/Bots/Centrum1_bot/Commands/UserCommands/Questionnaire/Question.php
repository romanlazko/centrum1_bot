<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\Questionnaire;

use App\Bots\Centrum1_bot\Commands\UserCommands\DataIsSend;
use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Bots\Centrum1_bot\Commands\UserCommands\Profile\Profile;
use App\Jobs\SendQuestionnaireAfter3Hours;
use App\Models\Questionnaire\QuestionButton;
use App\Models\Questionnaire\Questionnaire;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Question extends Command
{
    public static $command = 'question';

    public static $title = [
        'ru' => 'Вопрос',
        'en' => 'Question',
    ];

    public static $usage = ['question'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $questionButton = QuestionButton::find($updates->getInlineData()->getQuestionButtonId());

        $questionnaire = $questionButton->question->questionnaire;

        $question = $questionnaire->getNextQuestion($questionButton->question->id);

        if (!$question) {
            $telegram_chat = DB::getChat($updates->getChat()->getId());

            $questionnaire->answers()->where('telegram_chat_id', $telegram_chat->id)->first()->update([
                'finished'   => true,
            ]);
            
            return $this->bot->executeCommand(DataIsSend::$command);
        }

        $buttons = $question->question_buttons
            ->map(fn ($button) => [array($button->text, Answer::$command, $button->id)])
            ->toArray();

        $buttons = BotApi::inlineKeyboard($buttons, 'question_button_id');

        $text = implode("\n", [
            "*{$question->title}*"."\n",
            "{$question->body}"
        ]);

        return BotApi::returnInline([
            'text'                      => $text,
            'reply_markup'              => $buttons,
            'chat_id'                   => $updates->getChat()->getId(),
            'parse_mode'                => 'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
    }
}