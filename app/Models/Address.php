<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    protected $primaryKey = 'id';
    protected $fillable = ['longitude', 'latitude', 'description', 'postal_code', 'jalan', 'provinsi', 'kota', 'negara', 'id_user'];
    public $timestamps = true;
    
    public function store()
    {
        return $this->belongsTo(Store::class, 'id_store');
    }
}
