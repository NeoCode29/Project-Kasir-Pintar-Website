<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("users", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->timestamp("email_verified_at")->nullable();
            $table->string("password");
            $table->rememberToken();
            $table->string("number_phone")->nullable();
            $table
                ->enum("roles", ["administrator", "owner", "staff"])
                ->default("owner");
            $table->timestamps();
        });

        Schema::create("password_reset_tokens", function (Blueprint $table) {
            $table->string("email")->primary();
            $table->string("token");
            $table->timestamp("created_at")->nullable();
        });

        Schema::create("sessions", function (Blueprint $table) {
            $table->string("id")->primary();
            $table->foreignId("user_id")->nullable()->index();
            $table->string("ip_address", 45)->nullable();
            $table->text("user_agent")->nullable();
            $table->longText("payload");
            $table->integer("last_activity")->index();
        });

        Schema::create("notifications", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("user_id")
                ->constrained("users")
                ->onDelete("cascade");
            $table->string("message");
            $table->boolean("is_read")->default(false);
            $table->timestamps();
        });

        Schema::create("profiles", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->enum("gender", ["male", "female", "none"])->default("none");
            $table->integer("age")->unsigned();
            $table->string("address");
            $table->string("url_image");
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("profiles");
        Schema::dropIfExists("notifications");
        Schema::dropIfExists("sessions");
        Schema::dropIfExists("password_reset_tokens");
        Schema::dropIfExists("users");
    }
};
