<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Message extends Model
{
    use HasFactory;

    public function chat(): HasOne
    {
        return $this->hasOne('App\Models\Chat', 'id', 'chat_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne('App\Models\User', 'id', 'owner_id');
    }

    public function getCreatedAtAttribute($value): string
    {
        $date = Carbon::parse($value);
        return $date->format('Y-m-d H:i');
    }
    public function getUpdatedAtAttribute($value): string
    {
        $date = Carbon::parse($value);
        return $date->format('Y-m-d H:i');
    }
}
