<?php

namespace App\Http\Controllers;


use App\Models\Guild;
use App\Models\Role;
use App\Models\User;
use App\Models\VoiceChannels;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoiceController extends Controller
{
    public function index(){
        $data = json_decode(file_get_contents(storage_path('/configs.json')), true);
        return redirect($data['address']. $data['nodePort'] . '/guilds');
    }
    public function getVoice(){
        return Role::where('user_id', Auth::user()->id)->with('guild')->get();
    }

    public function getAmount($guild)
    {
        return DB::select('SELECT COUNT(*) as amount FROM role WHERE guild_id = ?', [$guild])[0];
    }

    public function haveAccessGuild(Request $request, $id): JsonResponse
    {
        $user = User::find($request->user_id);
        $guild = DB::select('SELECT guild_id FROM role WHERE user_id = ? AND guild_id = ?', [$user->id, $id]);
        if (count($guild) > 0) {
            return response()->json(['access' => true]);
        }
        return response()->json(['access' => false]);
    }

    public function getChannels(Request $request){
        return VoiceChannels::where("guild_id", "=", $request->guild_id)->get();
    }

    public function haveAccessVoice(Request $request, $id): JsonResponse
    {
        $user = User::find($request->user_id);
        $channel = DB::select('SELECT * FROM guilds
                                    INNER JOIN voice_channels vc on guilds.id = vc.guild_id
                                    INNER JOIN role r on guilds.id = r.guild_id
                                WHERE r.user_id = ? AND vc.id = ?', [$user->id, $id]);

        if (count($channel) > 0) {
            return response()->json(['access' => true]);
        }
        return response()->json(['access' => false]);
    }
}
