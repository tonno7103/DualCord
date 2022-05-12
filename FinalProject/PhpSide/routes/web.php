<?php

use App\Http\Controllers\{
    NewAccountController,
    NewChatController,
    NewEditProfileController,
    newImageController,
    VoiceController
};

use Illuminate\Support\Facades\Route;


Route::get('/', function (){
    $data = json_decode(file_get_contents(storage_path() . "/configs.json"), true);
    return redirect($data['address'] . $data['nodePort']);
})->name('home');


Route::get("/auth/login", NewAccountController::class . "@loginPage")->name("login");
Route::post("/auth/login", NewAccountController::class . "@login");
Route::get("/auth/register", NewAccountController::class . "@registerPage")->name("register");
Route::post("/auth/register", NewAccountController::class . "@register");
Route::get("/auth/logout", NewAccountController::class . "@logout")->middleware('is_logged');

Route::get("/dashboard", function (){
    $data = json_decode(file_get_contents(storage_path() . "/configs.json"), true);
    return view("dashboard", ["home" => $data['address'], "nodePort" => $data['nodePort'], "phpPort" => $data['phpPort'], "route" => "dashboard", "title" => "Dashboard"    ]);
})->middleware('is_logged');


Route::get('/user/edit', NewEditProfileController::class . "@index")->name('index')->middleware('is_logged');
Route::post("/user/uploadImage", NewImageController::class . "@uploadImage")->name('uploadImage')->middleware('is_logged');
Route::post("/user/removeImage", NewImageController::class . "@removeImage")->middleware('is_logged');
Route::post("/user/edit", newImageController::class . "@edit")->middleware('is_logged');

Route::get('/chat', NewChatController::class . "@index")->name('chat')->middleware('is_logged');
Route::get("/chats", NewChatController::class . "@chats")->name("chat")->middleware('is_logged');
Route::get("/group/create", NewChatController::class . "@createGroupPage")->middleware('is_logged');
Route::post("/guild/create", VoiceController::class . "@createGuild")->middleware('is_logged');
Route::post('/guild/createNewChannel', VoiceController::class . "@createNewChannel")->middleware('is_logged');

Route::get('/chat/{id}/messages', NewChatController::class . "@messages")->name('messages')->middleware('is_logged');
Route::post('/chat/{id}/message', NewChatController::class . "@newMessage")->name('newMessage')->middleware('is_logged');
Route::get('/chat/{id}/members', NewChatController::class . "@getMembers")->name('getMembers')->middleware('is_logged');
Route::get('/chat/{id}', NewChatController::class . "@getChatById")->name('getChatById')->middleware('is_logged');
Route::post('/chat/create', NewChatController::class . "@create")->name('create')->middleware('is_logged');
Route::get('/user/{id}', NewAccountController::class . "@getUser")->name('getUser');
Route::post('/chat/not-in-private', NewChatController::class . "@notInPrivate")->name('notInPrivate')->middleware('is_logged');
Route::post('/chat/not-in-private/{username}', NewChatController::class . "@notInPrivate")->name('notInPrivate')->middleware('is_logged');
Route::post('/group/get-users', NewChatController::class . "@getUsers")->name('getUsers')->middleware('is_logged');
Route::post('/group/create', NewChatController::class . "@create")->name('createGroup')->middleware('is_logged');

Route::get('/user/search/{username}', NewAccountController::class . "@search")->name('search')->middleware('is_logged');



Route::get('/guilds', VoiceController::class . "@index")->name('voice')->middleware('is_logged');
Route::get('/voices', VoiceController::class . "@getVoice")->name('getVoice')->middleware('is_logged');
Route::get('/voices/users-amount/{id}', VoiceController::class . "@getAmount")->name('getAmount')->middleware('is_logged');
Route::post("/guild/getChannels", VoiceController::class . "@getChannels")->name('getChannels')->middleware('is_logged'); // guild_id on post
Route::post('/user/have-access/guild/{id}', VoiceController::class . "@haveAccessGuild")->name('haveAccessGuild');
Route::post('/user/have-access/voice/{id}', VoiceController::class . "@haveAccessVoice")->name('haveAccessVoice');
Route::post("/guild/getLevel/{guild_id}", VoiceController::class . "@getLevel")->name('getLevel');


// invite system
Route::get("/g/invite/{uuid}", VoiceController::class . "@invite")->name('invite')->middleware('is_logged');
Route::post('/guild/invite/get-invite', VoiceController::class . "@getInviteCode")->name('getInviteCode')->middleware('is_logged'); // guild_id on post
