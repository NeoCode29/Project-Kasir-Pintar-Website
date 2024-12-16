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

        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_owner')->constrained("users")->onDelete('cascade');
            $table->string('name');
            $table->string('number_phone')->nullable()->default("");
            $table->timestamps();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->decimal('longitude', 8, 2);
            $table->decimal('latitude', 8, 2);
            $table->string('postal_code');
            $table->string('jalan')->default("");
            $table->foreignId('id_store')->constrained("stores")->onDelete('cascade');
            $table->string('provinsi');
            $table->string('kota');
            $table->string('negara');
            $table->timestamps();
        });

        Schema::create('staff', function (Blueprint $table) {
            $table->foreignId('id_user')->constrained("users")->onDelete('cascade');
            $table->foreignId('id_store')->constrained("stores")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('stores');
    }
};
