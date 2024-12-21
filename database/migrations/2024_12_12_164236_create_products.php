<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("category_products", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("name_img");
            $table->timestamps();
        });

        Schema::create("products", function (Blueprint $table) {
            $table->id();
            $table->string("name_product");
            $table->string("code_product");
            $table->decimal("selling_price", 13, 2);
            $table->decimal("purchase_price", 13, 2);
            $table->integer("stock");
            $table->string("unit");
            $table->string("url_image");
            $table
                ->foreignId("store_id")
                ->constrained("stores")
                ->onDelete("cascade");
            $table
                ->foreignId("category_product_id")
                ->constrained("category_products")
                ->onDelete("cascade");
            $table->timestamps();
        });

        Schema::create("discounts", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("store_id")
                ->constrained("stores")
                ->onDelete("cascade");
            $table->string("description");
            $table->decimal("discount_value", 8, 2);
            $table->decimal("minimum_purchase", 13, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("discounts");
        Schema::dropIfExists("products");
        Schema::dropIfExists("category_products");
    }
};
