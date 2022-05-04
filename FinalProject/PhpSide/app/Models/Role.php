<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Role extends Model
{
    use HasFactory;
    protected $table = 'role';
    protected $fillable = ['user_id', 'guild_id', 'name', 'permission_level'];
    protected $primaryKey = ['user_id', 'guild_id'];
    public $incrementing = false;
    public $timestamps = false;

    public function user(): HasOne
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function guild(): HasOne
    {
        return $this->hasOne('App\Models\Guild', 'id', 'guild_id');
    }
}
