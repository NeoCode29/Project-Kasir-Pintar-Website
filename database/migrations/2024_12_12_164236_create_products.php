<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name_product');
            $table->string('code_product');
            $table->decimal('selling_price', 8, 2);
            $table->decimal('purchase_price', 8, 2);
            $table->integer('stock');
            $table->string('unit');
            $table->string('url_image');
            $table->foreignId('id_store')->constrained("stores")->onDelete('cascade');
            $table->foreignId('id_category_product')->constrained("category_products")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_products');
        Schema::dropIfExists('products');
    }
};
