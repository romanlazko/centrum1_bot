<?php 

namespace App\Bots\Centrum1_bot\Commands\UserCommands\Questionnaire;

use App\Bots\Centrum1_bot\Commands\UserCommands\MenuCommand;
use App\Jobs\SendQuestionnaireAfter3Hours;
use App\Models\Questionnaire\QuestionButton;
use App\Models\Questionnaire\Questionnaire;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Answer extends Command
{
    public static $command = 'question_answer';

    public static $title = [
        'ru' => 'Ответ',
        'en' => 'Answer',
    ];

    public static $usage = ['question_answer'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $questionButton = QuestionButton::find($updates->getInlineData()->getQuestionButtonId());

        $questionnaire = $questionButton->question->questionnaire;

        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $answer = $questionnaire->answers()->where('telegram_chat_id', $telegram_chat->id)->first();

        $answers = [];

        if (is_array($answer->answers)) {
            $answers = $answer->answers;
        }

        $answers[$questionButton->question->id] = $questionButton->id;

        $answer->update([
            'telegram_chat_id' => $telegram_chat->id,
            'answers' => $answers,
        ]);


        return $this->bot->executeCommand(Question::$command);
    }
}