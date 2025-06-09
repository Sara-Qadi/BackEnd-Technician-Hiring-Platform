<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\role;


class MessagesController extends Controller
{
    public function storeMessage(Request $request)
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
        $messages = Message::whereRaw('
            (sender_id = ? AND receiver_id = ?)
            OR (sender_id = ? AND receiver_id = ?)', 
            [$sender_id, $receiver_id, $receiver_id, $sender_id])->
            orderby('id', 'asc')->get();

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

        $users = \DB::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.role_id')
        ->whereIn('users.user_id', $userIds)
        ->select('users.user_id', 'users.user_name', 'roles.name as role_name')
        ->get();

        return response()->json($users);
    }

    public function getSelectedUserToMessage($sender_id , $receiver_id)
    {
        $message = Message::create([
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'message_content' => 'hi, there',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);
    }


}
