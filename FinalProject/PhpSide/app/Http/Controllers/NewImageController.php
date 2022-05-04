<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Imagick;
use Intervention\Image\Facades\Image;

class NewImageController extends Controller
{
    public array $data;

    public function __construct()
    {
        $this->data = json_decode(file_get_contents(storage_path() . "/configs.json"), true);
    }

    public function uploadImage(Request $request){
        $credentials = $request->validate([
            "imageInput" => "required|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp|max:200000",
        ]);
        $user = Auth::user();

        if($user->image_format != null && File::delete(public_path('images/'. $user->id . "." . $user->image_format)))
            if(File::exists(public_path('images/' . $user->id . "." . $user->image_format)))
                File::delete(public_path('images/'. $user->id . "." . $user->image_format));


        $image = $credentials['imageInput'];
        $imageName = $user->id . "." . $image->getClientOriginalExtension();
        if($image->getClientOriginalExtension() == 'gif'){
            $image->move("images/" , $imageName);
        }
        else{
            $img = Image::make($image->path());
            $img->resize(128, 128, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('images/' . $imageName));
        }

        $user->image_format = $image->getClientOriginalExtension();
        $user->save();

        session()->put("editProfileSuccess", "Image updated successfully");
        return redirect()->back();
    }

    public function removeImage(){

        $user = Auth::user();
        if($user->image_format != null && File::delete(public_path('images/'. $user->id . "." . $user->image_format)))
            if(File::exists(public_path('images/' . $user->id . "." . $user->image_format)))
                File::delete(public_path('images/'. $user->id . "." . $user->image_format));
        $user->image_format = null;
        $user->save();

        session()->put("editProfileSuccess", "Image removed successfully");
        return redirect()->back();
    }

    public function edit(Request $request){
        $credentials = $request->validate([
            "email" => "required|email",
            "currentPassword" => "required",
            "passwordChange" => "string|nullable|min:8",
            "passwordConfirm" => "string|nullable|same:passwordChange",
            "username" => "required|string"
        ]);
        $user = Auth::user();

        if(!Hash::check($credentials['currentPassword'], $user->password))
            return redirect()->back()->withErrors(["currentPassword" => "Password is incorrect"]);

        if($credentials['passwordChange'] != null)
            if(Hash::check($credentials['passwordChange'], $credentials['currentPassword'])){
                return redirect()->back()->withErrors(["passwordChange" => "New password is the same as the current one"]);
            }
            else if($credentials['passwordChange'] == $credentials['passwordConfirm']){
                $user->password = Hash::make($credentials['passwordChange']);
            }
            else{
                return redirect()->back()->withErrors(["passwordChange" => "New password and confirmation do not match"]);
            }

        if($credentials['username'] != null)
            $user->username = $credentials['username'];

        if($credentials['email'] != null)
            $user->email = $credentials['email'];

        $user->save();
        session(["editProfileSuccess" => "Profile updated successfully"]);
        return redirect()->back();

    }
}
