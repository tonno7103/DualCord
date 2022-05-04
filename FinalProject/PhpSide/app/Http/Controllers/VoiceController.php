<?php

namespace App\Http\Controllers;


use App\Models\Guild;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoiceController extends Controller
{
    public function index(){
        $data = json_decode(file_get_contents(storage_path('/configs.json')), true);
        return redirect($data['address']. $data['nodePort'] . '/voice');
    }
    public function getVoice(){
        return Role::where('user_id', Auth::user()->id)->with('guild')->get();
    }

    function getAmount($guild)
    {
        return DB::select('SELECT COUNT(*) as amount FROM role WHERE guild_id = ?', [$guild])[0];
    }
}
