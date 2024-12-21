<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $table = "invitations";

    protected $fillable = [
        "inviter_id",
        "store_id",
        "notification_id",
        "is_accept",
    ];

    public function notification()
    {
        return $this->belongsTo(Notification::class, "id", "notification_id");
    }
}
