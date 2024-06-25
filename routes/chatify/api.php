<?php

use App\Http\Controllers\vendor\Chatify\Api\MessagesController;
use Illuminate\Support\Facades\Route;

/**
 * Authentication for pusher private channels
 */
Route::post('/chat/auth', 'MessagesController@pusherAuth')->name('api.pusher.auth');

Route::middleware(['loggedIn','hasAnyRole:client,message'])->group(function(){
    /**
     * Send message route
     */
    Route::post('/sendMessage', 'MessagesController@send')->name('api.send.message');
    
    /**
     * Fetch messages
     */
    Route::post('/fetchMessages', 'MessagesController@fetch')->name('api.fetch.messages');
    
    /**
     * Download attachments route to create a downloadable links
     */
    Route::get('/download/{fileName}', 'MessagesController@download')->name('api.'.config('chatify.attachments.download_route_name'));
    
    /**
     * Make messages as seen
     */
    Route::post('/makeSeen', 'MessagesController@seen')->name('api.messages.seen');
    
    /**
     * Get contacts
     */
    Route::get('/getContacts', 'MessagesController@getContacts')->name('api.contacts.get');

    /**
     * Set active status
     */
    Route::post('/setActiveStatus', 'MessagesController@setActiveStatus')->name('api.activeStatus.set');

    /**
     * Delete message
     */
    Route::post('/deleteMessage', [MessagesController::class, 'deleteMessage'])->name('message.delete');


    Route::get('setTyping', 'MessagesController@setTyping')->name('api.setTyping');
});

Route::middleware(['loggedIn','message'])->group(function(){
   /**
     * Delete Conversation
     */
    Route::post('/deleteConversation', 'MessagesController@deleteConversation')->name('api.conversation.delete');
});

/**
 *  Fetch info for specific id [user/group]
 */



