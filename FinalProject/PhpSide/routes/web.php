<?php

use App\Http\Controllers\{NewAccountController, NewChatController, NewEditProfileController, newImageController};
use Illuminate\Support\Facades\Route;

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
Route::get('/chat/{id}/messages', NewChatController::class . "@messages")->name('messages')->middleware('is_logged');
Route::post('/chat/{id}/message', NewChatController::class . "@newMessage")->name('newMessage')->middleware('is_logged');
Route::get('/chat/{id}/members', NewChatController::class . "@getMembers")->name('getMembers')->middleware('is_logged');
Route::get('/chat/{id}', NewChatController::class . "@getChatById")->name('getChatById')->middleware('is_logged');
Route::post('/chat/create', NewChatController::class . "@create")->name('create')->middleware('is_logged');
Route::get('/user/{id}', NewAccountController::class . "@getUser")->name('getUser')->middleware('is_logged');


Route::get('/user/search/{username}', NewAccountController::class . "@search")->name('search')->middleware('is_logged');
// Route::post("/auth/login", [AccountController::class, "login"]);
// Route::get("/auth/logout", function(){
//     session()->flush();
//     return view("auth.logout");
// });

// Route::post("/auth/register", [AccountController::class, "register"]);
// Route::get("/auth/register", function(){
//     return view("auth.register");
// });

// Route::get("/auth/login", function (){
//     return view("auth.login");
// });

// Route::middleware([CheckLogin::class])->group(function(){
//     Route::get("/user/edit", function (){
//         $data = json_decode(file_get_contents(storage_path() . "/configs.json"), true);
//         return view("editProfile.editProfile", ["home" => $data['address'], "nodePort" => $data['nodePort'], "phpPort" => $data['phpPort'], "route" => "edit", "userId" => session()->get("user")]);
//     });
//     Route::post("/user/edit", [AccountController::class, "edit"]);
//     Route::post("/user/uploadImage", [AccountController::class, "uploadImage"]);
//     Route::post('/user/removeImage', [AccountController::class, "removeImage"]);
//     Route::get('/dashboard', function(){
//         $data = json_decode(file_get_contents(storage_path() . "/configs.json"), true);
//         return view("dashboard", ["home" => $data['address'], "nodePort" => $data['nodePort'], "phpPort" => $data['phpPort'], "route" => "dashboard", "userId" => session()->get("user")]);
//     });

//     Route::get('directs', \App\Http\Controllers\NewEditProfileController::class . "@index");
// });


// Route::post('/fetchMessages', [ChatController::class, "fetchMessages"]);
// Route::post('/sendMessage', [ChatController::class, "sendMessage"]);
// Route::post('/searchUsername', [ChatController::class, "searchUsername"]);
// Route::post('/startChat', [ChatController::class, "startChat"]);