<?php

namespace App\Jobs;

use App\Bots\Centrum1_bot\Commands\AdminCommands\MenuCommand;
use App\Bots\Centrum1_bot\Commands\UserCommands\Questionnaire\Answer;
use App\Models\Questionnaire\Questionnaire;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\Models\TelegramBot;

class SendQuestionnaire implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Questionnaire $questionnaire;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $questionnaire_id, protected $chat_id)
    {
        $this->questionnaire = Questionnaire::find($questionnaire_id);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $bot = new Bot(env('TELEGRAM_BOT_TOKEN', TelegramBot::first()->token));

        $question = $this->questionnaire->questions()->first();

        $telegram_chat = DB::getChat($this->chat_id);

        $answer = $this->questionnaire->answers()->updateOrCreate([
            'telegram_chat_id' => $telegram_chat->id,
        ]);

        if (!is_null($answer->answers)) {
            return;
        }

        $buttons = $question->question_buttons
            ->map(fn ($button) => [array($button->text, Answer::$command, $button->id)])
            ->toArray();

        $buttons = BotApi::inlineKeyboard($buttons, 'question_button_id');

        $text = implode("\n", [
            "*{$question->title}*"."\n",
            "{$question->body}"
        ]);

        $bot::sendMessage([
            'text'                      => $text,
            'reply_markup'              => $buttons,
            'chat_id'                   => $this->chat_id,
            'parse_mode'                => 'Markdown',
        ]);
    }

    public function uniqueId(): string
    {
        return $this->questionnaire_id . '_' . $this->chat_id;
    }
}
