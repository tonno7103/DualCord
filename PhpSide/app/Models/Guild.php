<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guild extends Model
{
    use HasFactory;

    protected $table = 'guilds';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'owner_id', 'invite_code'];
    public $timestamps = false;

    public function users()
    {
        return $this->hasOne('App\Models\Role', 'id', 'owner_id');
    }

    public function roles()
    {
        return $this->hasMany('App\Models\Role', 'guild_id', 'id');
    }

    public function voiceChannels()
    {
        return $this->hasMany('App\Models\VoiceChannels', 'guild_id', 'id');
    }
}
