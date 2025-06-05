<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,user_id',
            'receiver_id' => 'required|exists:users,user_id',
            'message_content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message_content' => $request->message_content,
        ]);

        return response()->json(['message' => 'Message stored successfully', 'data' => $message], 201);
    }

    public function getConversation($sender_id, $receiver_id)
    {
        $messages = Message::where([
            ['sender_id', '=', $sender_id],
            ['receiver_id', '=', $receiver_id]
        ])->orWhere([
            ['sender_id', '=', $receiver_id],
            ['receiver_id', '=', $sender_id]
        ])
        ->orderBy('id', 'desc')
        ->get();

        return response()->json($messages);
    }

    public function getUserConversations($user_id)
    {
        $messages = Message::where('sender_id', $user_id)
            ->orWhere('receiver_id', $user_id)
            ->get();

        $userIds = [];

        foreach ($messages as $msg) {
            if ($msg->sender_id != $user_id && !in_array($msg->sender_id, $userIds)) {
                $userIds[] = $msg->sender_id;
            }

            if ($msg->receiver_id != $user_id && !in_array($msg->receiver_id, $userIds)) {
                $userIds[] = $msg->receiver_id;
            }
        }

        $users = User::whereIn('user_id', $userIds)->get();

        return response()->json($users);
    }
}
