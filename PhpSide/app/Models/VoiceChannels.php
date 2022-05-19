<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoiceChannels extends Model
{
    use HasFactory;

    protected $table = 'voice_channels';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'guild_id', 'users_limit'];

    public function guild()
    {
        return $this->hasOne('App\Models\Guilds', 'id', 'guild_id');
    }

}
