<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("stores", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("owner_id")
                ->constrained("users")
                ->onDelete("cascade");
            $table->string("name");
            $table->string("number_phone")->nullable()->default("");
            $table->timestamps();
        });

        Schema::create("addresses", function (Blueprint $table) {
            $table->id();
            $table->decimal("longitude", 10, 8);
            $table->decimal("latitude", 10, 8);
            $table->string("postal_code");
            $table->string("jalan")->default("");
            $table
                ->foreignId("store_id")
                ->constrained("stores")
                ->onDelete("cascade");
            $table->string("provinsi");
            $table->string("kota");
            $table->string("negara");
            $table->timestamps();
        });

        Schema::create("staff", function (Blueprint $table) {
            $table
                ->foreignId("user_id")
                ->constrained("users")
                ->onDelete("cascade");
            $table
                ->foreignId("store_id")
                ->constrained("stores")
                ->onDelete("cascade");
            $table->timestamps();
        });

        Schema::create("invitations", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("inviter_id")
                ->constrained("users")
                ->onDelete("cascade");
            $table
                ->foreignId("store_id")
                ->constrained("stores")
                ->onDelete("cascade");
            $table->boolean("is_accept")->default(false);
            $table
                ->foreignId("notification_id")
                ->constrained("notifications")
                ->onDelete("cascade");
            $table->timestamp("created_at")->nullable();
        });

        Schema::create("suppliers", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("store_id")
                ->constrained("stores")
                ->onDelete("cascade");
            $table->string("name");
            $table->string("number_phone")->nullable()->default("");
            $table->string("address");
            $table->string("email");
            $table->timestamps();
        });

        Schema::create("customers", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("store_id")
                ->constrained("stores")
                ->onDelete("cascade");
            $table->string("name");
            $table->string("number_phone")->nullable()->default("");
            $table->string("address");
            $table->string("email");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("customers");
        Schema::dropIfExists("suppliers");
        Schema::dropIfExists("invitations");
        Schema::dropIfExists("addresses");
        Schema::dropIfExists("staff");
        Schema::dropIfExists("stores");
    }
};
