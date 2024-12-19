<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ["gender","age","address","url_image","id_user"];

    public function user(){
        return $this->belongsTo(Users::class, "user_id");
    }
}
