<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = ["name", "email", "password", "number_phone", "role"];

    protected $hidden = ["password", "remember_token"];

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    public function stores()
    {
        return $this->hasMany(Store::class, "owner_id");
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, "user_id");
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, "user_id");
    }
}
