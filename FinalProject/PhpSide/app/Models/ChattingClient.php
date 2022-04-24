<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ChattingClient extends Model
{
    use HasFactory;

    protected $table = 'chatting_client';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'chat_id'];
    public $timestamps = false;

    public function user(): HasOne
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function chat(): HasOne
    {
        return $this->hasOne('App\Models\Chat', 'id', 'chat_id');
    }

}
