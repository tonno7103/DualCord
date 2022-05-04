<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function messages(): HasMany
    {
        return $this->hasMany('App\Models\Message');
    }

    public function chattingClient(): HasMany
    {
        return $this->hasMany('App\Models\ChattingClient');
    }

}
