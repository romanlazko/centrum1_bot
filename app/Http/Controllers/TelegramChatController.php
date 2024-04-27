<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\App\Config;
use Romanlazko\Telegram\Exceptions\TelegramException;
use Romanlazko\Telegram\Models\TelegramBot;
use Romanlazko\Telegram\Models\TelegramChat;

class TelegramChatController extends Controller
{
    public function index()
    {
        return view('admin.telegram.chat.index');
    }

    public function show(TelegramChat $telegram_chat)
    {
        $bot = new Bot(auth()->user()->telegram_bot->token);

        $telegram_chat->photo = $bot::getPhoto(['file_id' => $telegram_chat->photo]);

        $messages = $telegram_chat->messages()
            ->orderBy('id', 'DESC')
            ->with(['user', 'callback_query', 'callback_query.user'])
            ->paginate(20);

        $messages->map(function ($message) use ($bot){
            if ($message->photo) {
                $message->photo = $bot::getPhoto(['file_id' => $message->photo]);
            }
        });

        return view('admin.telegram.chat.show', compact(
            'telegram_chat',
            'messages'
        ));
    }

    public function send_message(Request $request, TelegramChat $telegram_chat)
    {
        try {
            $bot = new Bot(auth()->user()->telegram_bot->token);

            $response = $bot::sendMessage([
                'text'          => $request->message,
                'chat_id'       => $telegram_chat->chat_id,
            ]);
            
            return back()->with([
                'ok' => $response->getOk(), 
                'description' => "Message successfully sent"
            ]);
        }
        catch (TelegramException $e){
            return back()->with([
                'ok' => false, 
                'description' => $e->getMessage()
            ]);
        }
    }
}
