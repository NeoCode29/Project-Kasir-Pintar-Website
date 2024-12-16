<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'stores';
    protected $primaryKey = 'id';
    protected $fillable = ['id_owner', 'name', 'number_phone'];
    public $timestamps = true;

    public function owner()
    {
        return $this->belongsTo(User::class, 'id_owner');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'id_store');
    }
}
