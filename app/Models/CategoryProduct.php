<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    protected $fillable = ["name", "url_image"];
    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
