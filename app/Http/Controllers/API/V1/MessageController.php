<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\IndexChatMessagesRequest;
use App\Http\Requests\SendMessageAsClientRequest;
use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\ChatRoom;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $distinctSenderMessages = Message::whereIn('id', function($query) {
            $query->select(DB::raw('MAX(id)'))
                ->from('messages')
                ->where('receiver_id', 3)
                ->groupBy('sender_id');
        })
        ->with('sender') 
        ->latest()
        ->paginate();

        return $this->respondOk($distinctSenderMessages);
    }

    public function index_chat_messages(IndexChatMessagesRequest $request)
    {
        $messages = Message::where("sender_id" , $request->user_id)
                        ->where("receiver_id" , $request->receiver_id)
                        ->latest()
                        ->paginate();

        return $this->respondOk($messages);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request)
    {
        $data = $request->validated();
        $user = $request->user;

        $data['receiver_id'] = $data['user_id'];
        $data['sender_id'] = $user->id;
        
        $message = Message::create($data);

        broadcast(new \App\Events\MessageSent($message));
        return response()->json(['message' => $message], 200);
    }

    public function send_as_client(SendMessageAsClientRequest $request)
    {
        $data = $request->validated();
        $user = $request->user;

        $data['receiver_id'] = 3; // the id of the admin with role message
        $data['sender_id'] = $user->id;
        
        $message = Message::create($data);

        broadcast(new \App\Events\MessageSent($message));
        return response()->json(['message' => $message], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
