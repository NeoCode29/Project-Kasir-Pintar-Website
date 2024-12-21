<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ["user_id", "message", "is-read"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invite()
    {
        return $this->hasOne(Invite::class);
    }
}
