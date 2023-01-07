<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Http\Requests\MessageRequest;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Tymon\JWTAuth\Facades\JWTAuth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }
    public function getRooms()
    {
        return ChatRoom::get();
    }

    public function getMessages($roomId)
    {
        return ChatMessage::where("chat_room_id", $roomId)
            ->with("user")
            ->orderBy("created_at", "DESC")->get();
    }

    public function newMesage(MessageRequest $request)
    {
        $authUserId = JWTAuth::parseToken()->getPayload()->get("sub");
        $request->merge(["user_id" => $authUserId]);
        $message = ChatMessage::create($request->input());
        broadcast(new NewChatMessage($message));
        return $message;
    }
}
