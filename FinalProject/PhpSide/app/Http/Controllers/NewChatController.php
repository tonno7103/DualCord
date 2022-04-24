<?php

namespace App\Http\Controllers;

use App\Events\NewChat;
use App\Models\ChattingClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{View, Factory};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\{Request};
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Events\newChatMessage;

class NewChatController extends Controller
{
    public array $data;

    public function __construct()
    {
        $this->data = json_decode(file_get_contents(storage_path() . "/configs.json"), true);
    }

    public function index(): Factory|View|Application
    {
        return view('chat', [
            'home' => $this->data['address'],
            'nodePort' => $this->data['nodePort'],
            'phpPort' => $this->data['phpPort'],
            'route' => 'chat'
        ]);
    }

    public function chats(): \Illuminate\Support\Collection
    {
        $chats = ChattingClient::where('user_id', Auth::id())->get();
        return $chats->map(function ($chat) {
            $chat->chat = Chat::find($chat->chat_id);
            $chat->chat->messages = Message::where('chat_id', $chat->chat_id)->get();
            return $chat;
        });
    }

    public function messages(Request $request, $chat_id): Collection
    {
        return Message::where('chat_id', $chat_id)->with('user')->orderBy('id', 'asc')->get();
    }

    public function newMessage(Request $request, $chat_id): Message
    {
        $message = new Message();
        $message->chat_id = $chat_id;
        $message->owner_id = Auth::id();
        $message->message = $request->get('message');
        $message->save();
        broadcast(new newChatMessage($message))->toOthers();
        return $message->load('user');
    }

    public function getChats(): Collection
    {
        return Chat::all();
    }

    public function getMembers(Request $request, $chat_id): \Illuminate\Support\Collection
    {
        return ChattingClient::where('chat_id', $chat_id)->with('user')->get();
    }

    public function create(Request $request): mixed
    {
        if(!$request->get('is_group')) {
            $chat = new Chat();
            $chat->is_group = false;
            $chat->name = 'NormalPrivateChat';
            $chat->save();

            $chatting1 = new ChattingClient();
            $chatting1->user_id = Auth::id();
            $chatting1->chat_id = $chat->id;

            $chatting2 = new ChattingClient();
            $chatting2->user_id = $request->get('user_id');
            $chatting2->chat_id = $chat->id;

            $chatting2->save();
            $chatting1->save();
            broadcast(new NewChat($chatting2))->toOthers();
            return response()->json([$chatting2->load('user'), $chat]);

//            $chat->ChattingClient()->user()->attach(Auth::id());
//            $chat->ChattingClient()->attach($request->get('user_id'));
//            $chat->save();
//            return $chat;
        }
        return response()->json(['error' => "Not implemented yet"], 403);
    }

    public function getChatById(Request $request, $chat_id): Chat
    {

        return Chat::find($chat_id);
    }
}
