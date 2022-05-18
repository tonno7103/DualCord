<?php

namespace App\Http\Controllers;


use App\Models\Guild;
use App\Models\Role;
use App\Models\User;
use App\Models\VoiceChannels;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VoiceController extends Controller
{
    public function index(){
        $data = json_decode(file_get_contents(storage_path('/configs.json')), true);
        return redirect($data['address']. $data['nodePort'] . '/guilds');
    }

    public function getVoice(){
        return Role::where('user_id', Auth::id())
            ->where('is_banned', false)
            ->where('is_left', false)
            ->with('guild')
            ->get();
    }
    public function getAmount($guild)
    {
        return DB::select('SELECT COUNT(*) as amount FROM role WHERE guild_id = ?', [$guild])[0];
    }

    public function haveAccessGuild(Request $request, $id): JsonResponse
    {
        $user = User::find($request->user_id);
        $guild = DB::select('SELECT guild_id FROM role WHERE user_id = ? AND guild_id = ? AND is_banned = false AND is_left = false', [$user->id, $id]);
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
        $channel = DB::select('
            SELECT * FROM guilds
                INNER JOIN voice_channels vc on guilds.id = vc.guild_id
                INNER JOIN role r on guilds.id = r.guild_id
            WHERE r.user_id = ? AND vc.id = ? AND r.is_banned = false AND r.is_left = false',
            [$user->id, $id]);

        if (count($channel) > 0) {
            return response()->json(['access' => true]);
        }
        return response()->json(['access' => false]);
    }

    public function createGuild(Request $request): JsonResponse
    {
        $guild = new Guild();
        $guild->name = $request->name;
        $guild->owner_id = Auth::id();
        $guild->invite_code = Str::uuid()->toString();
        $guild->save();
        $role = new Role();
        $role->guild_id = $guild->id;
        $role->user_id = Auth::id();
        $role->name = "Owner";
        $role->permission_level = 2;
        $role->save();
        return response()->json(['success' => true]);

    }

    public function createNewChannel(Request $request): VoiceChannels{
        $channel = new VoiceChannels();
        $channel->guild_id = $request->guild_id;
        $channel->name = $request->name;
        $channel->save();
        return $channel;
    }

    public function getLevel(Request $request, $guild_id): JsonResponse{
        $user = User::find($request->user_id);
        $role = DB::select(
            'SELECT * FROM role WHERE user_id = ? AND guild_id = ? AND is_banned = false AND is_left = false ',
            [$user->id, $guild_id]
        );
        if (count($role) > 0) {
            return response()->json(['level' => $role[0]->permission_level]);
        }
        return response()->json(['level' => -1]);
    }

    public function invite(Request $request, $uuid): RedirectResponse|JsonResponse
    {
        $data = json_decode(file_get_contents(storage_path('/configs.json')), true);
        $guild = Guild::where('invite_code', $uuid)->first();
        if ($guild) {
            $role = Role::where('user_id', Auth::id())->where('guild_id', $guild->id)->first();
            if(!$role){
                $role = new Role();
                $role->guild_id = $guild->id;
                $role->user_id = Auth::id();
                $role->name = "Member";
                $role->permission_level = 1;
                $role->save();
                return redirect($data['address'] . $data['nodePort'] . '/guild/' . $guild->id);
            }
            else if(!$role->is_banned){
                DB::update('UPDATE role SET is_left = false WHERE user_id = ? AND guild_id = ?', [Auth::id(), $guild->id]);
                return redirect($data['address'] . $data['nodePort'] . '/guild/' . $guild->id);
            }
        }
        return redirect($data['address'] . $data['nodePort']);
    }

    public function getInviteCode(Request $request): JsonResponse
    {
        $guild = Guild::find($request->guild_id);

        if ($guild && $guild->owner_id == Auth::id()) {
            return response()->json(['success' => true ,'invite_code' => $guild->invite_code]);
        }
        return response()->json(['success' => false, 'invite_code' => 'You are not the owner of this guild']);
    }

    public function leaveGuild(Request $request, $guild_id): JsonResponse
    {
        $guild = Guild::find($guild_id);
        $role = Role::where('user_id', Auth::id())->where('guild_id', $guild_id)->first();
        if ($guild && $role) {
            // set is_left to true without use models
            DB::update('UPDATE role SET is_left = true WHERE user_id = ? AND guild_id = ?', [Auth::id(), $guild_id]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function getLowerLevelMembers(Request $request, $guild_id): JsonResponse{
        $guild = Guild::find($guild_id);
        $role = Role::where('user_id', Auth::id())->where('guild_id', $guild_id)->first();
        if ($guild && $role && $role->permission_level >= 1) {
            $members = DB::select('
                SELECT username, image_format, user_id FROM role
                    INNER JOIN users ON role.user_id = users.id
                WHERE guild_id = ? AND permission_level < ? AND is_banned = false AND is_left = false AND user_id != ?
                LIMIT 5',
                [$guild_id, $role->permission_level, Auth::id()]
            );
            return response()->json(['success' => true, 'members' => $members]);
        }
        return response()->json(['success' => false]);
    }

    public function kickUser(Request $request, $guild_id, $target_id): JsonResponse
    {
        $guild = Guild::find($guild_id);
        $role = Role::where('user_id', Auth::id())->where('guild_id', $guild_id)->first();
        if ($guild && $role && $role->permission_level >= 1) {
            $target_role = Role::where('user_id', $target_id)->where('guild_id', $guild_id)->first();
            if ($target_role && $target_role->permission_level < $role->permission_level) {
                DB::update('UPDATE role SET is_left = true WHERE user_id = ? AND guild_id = ?', [$target_id, $guild_id]);
                return response()->json(['success' => true]);
            }
        }
        return response()->json(['success' => false]);
    }

    public function getUsersLowerLevel(Request $request, $guild_id, $username):JsonResponse {
        $guild = Guild::find($guild_id);
        $role = Role::where('user_id', Auth::id())->where('guild_id', $guild_id)->first();
        if ($guild && $role && $role->permission_level >= 1) {
            $users = DB::select('
                SELECT username, image_format, user_id FROM role
                    INNER JOIN users ON role.user_id = users.id
                WHERE guild_id = ? AND permission_level < ? AND is_banned = false AND is_left = false AND username LIKE ?
                LIMIT 10',
                [$guild_id, $role->permission_level, '%'.$username.'%']
            );
            return response()->json(['success' => true, 'users' => $users]);
        }
        return response()->json(['success' => false]);
    }

    public function banUser(Request $request, $guild_id, $target_id): JsonResponse{
        $guild = Guild::find($guild_id);
        $role = Role::where('user_id', Auth::id())->where('guild_id', $guild_id)->first();
        if ($guild && $role && $role->permission_level >= 2) {
            $target_role = Role::where('user_id', $target_id)->where('guild_id', $guild_id)->first();
            if ($target_role && $target_role->permission_level < $role->permission_level) {
                DB::update('UPDATE role SET is_banned = true WHERE user_id = ? AND guild_id = ?', [$target_id, $guild_id]);
                return response()->json(['success' => true]);
            }
        }
        return response()->json(['success' => false]);
    }
}
