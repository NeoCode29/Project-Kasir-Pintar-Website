<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        "name_product",
        "code_product",
        "selling_price",
        "purchase_price",
        "stock",
        "unit",
        "url_image",
        "store_id",
        "category_product_id",
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, "store_id");
    }

    public function categoryProduct()
    {
        return $this->hasOne(
            CategoryProduct::class,
            "id",
            "category_product_id"
        );
    }
}
