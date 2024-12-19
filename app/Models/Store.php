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
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'store_id');
    }
}
