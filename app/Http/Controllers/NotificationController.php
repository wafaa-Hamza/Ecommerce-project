<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\UsersNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendNotificationToAllUsers(Request $request)
    {

        $request->validate([
            'message'=>'required|string',
        ]);
        $message = $request->input('message');


        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new UsersNotification($message));
        }

        return response()->json(['status' => 'Notification Sent To All Users Successfully']);
    }

}
