<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewAccountController extends Controller
{
    public array $data;

    public function __construct()
    {
        $this->data = json_decode(file_get_contents(storage_path() . "/configs.json"), true);
    }

    public function loginPage(){
        return view('auth.login');
    }

    public function registerPage(){
        return view('auth.register');
    }

    public function logout(){
        Auth::logout();
        return view('auth.logout', ['data' => $this->data]);
    }

    public function logon($email, $password){
        if(Auth::attempt(['email' => $email, 'password' => $password], true)){
            Auth::login(User::where('email', $email)->first(), true);
            $user_id = Auth::user()->id;
            return view('auth.loginValidate', ['id' => $user_id]);
        }
        return redirect()->back()->withErrors(['error' => 'Email or password is incorrect']);
    }

    public function login(Request $request){
        $validation = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if(!Auth::validate($validation)):
            return redirect()->back()->withErrors(['error' => 'Email or password is incorrect']);
        endif;
        return $this->logon($request->email, $request->password);
    }

    public function register(Request $request){
        $validation = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'username' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);

        User::create([
            'email' => $validation['email'],
            'password' => password_hash($validation['password'], PASSWORD_DEFAULT),
            'username' => $validation['username']
        ]);

        return $this->logon($validation['email'], $validation['password']);
    }

    public function getUser(Request $request, $user_id){
        $user = User::where('id', $user_id)->first();
        return response()->json(['username' => $user->username, 'image_format' => $user->image_format]);
    }

    public function search(Request $request, $username){
        $users = User::where('username', 'like', '%' . $username . '%')->where('id', '!=', Auth::id())->get();
        return $users;
    }

}
