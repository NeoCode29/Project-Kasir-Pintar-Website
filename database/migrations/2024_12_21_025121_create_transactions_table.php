<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("selling_transactions", function (Blueprint $table) {
            $table->id();
            $table->foreignId("store_id")->constrained()->onDelete("cascade");
            $table->dateTime("data_transaction");
            $table->decimal("total_discount", 10, 2); // Adjust precision and scale as needed
            $table->boolean("is_debt");
            $table->text("description")->nullable();
            $table->enum("method", ["cash", "qris"]);
            $table->decimal("total_purchase", 10, 2); // Adjust precision and scale as needed
            $table->decimal("total_payment", 10, 2); // Adjust precision and scale as needed
            $table->decimal("change", 10, 2)->nullable(); // Change can be null
            $table->enum("status", ["done", "pending"]);
            $table->timestamps();
        });

        Schema::create("selling_detail_transactions", function (
            Blueprint $table
        ) {
            $table->id();
            $table
                ->foreignId("transaction_id")
                ->constrained("selling_transactions")
                ->onDelete("cascade");
            $table->foreignId("product_id")->constrained()->onDelete("cascade");
            $table->integer("quantity");
            $table
                ->foreignId("discount_id")
                ->nullable()
                ->constrained("discounts")
                ->onDelete("set null");
            $table->decimal("subtotal", 10, 2);
            $table->timestamps();
        });

        Schema::create("purchase_transactions", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("supplier_id")
                ->constrained()
                ->onDelete("cascade");
            $table->dateTime("purchase_date");
            $table->decimal("total_discount", 10, 2);
            $table->boolean("is_debt");
            $table->text("description")->nullable();
            $table->enum("method", ["cash", "transfer"]);
            $table->decimal("total_purchase", 10, 2);
            $table->decimal("total_payment", 10, 2);
            $table->decimal("change", 10, 2)->nullable();
            $table->enum("status", ["done", "pending"]);
            $table->timestamps();
        });

        Schema::create("purchase_detail_transactions", function (
            Blueprint $table
        ) {
            $table->id();
            $table
                ->foreignId("transaction_id")
                ->constrained("purchase_transactions")
                ->onDelete("cascade");
            $table->foreignId("product_id")->constrained()->onDelete("cascade");
            $table->integer("quantity");
            $table
                ->foreignId("discount_id")
                ->nullable()
                ->constrained("discounts")
                ->onDelete("set null");
            $table->decimal("subtotal", 10, 2);
            $table->timestamps();
        });
        Schema::create("receivables", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("transaction_id")
                ->constrained("selling_transactions")
                ->onDelete("cascade");
            $table
                ->foreignId("customer_id")
                ->constrained()
                ->onDelete("cascade");
            $table->decimal("subtotal", 10, 2);
            $table->enum("status", ["paid", "unpaid"]);
            $table->date("due_date");
            $table->timestamps();
        });

        Schema::create("payables", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("transaction_id")
                ->constrained("purchase_transactions")
                ->onDelete("cascade");
            $table
                ->foreignId("supplier_id")
                ->constrained()
                ->onDelete("cascade");
            $table->decimal("subtotal", 10, 2);
            $table->enum("status", ["paid", "unpaid"]);
            $table->date("due_date");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("selling_transactions");
        Schema::dropIfExists("selling_detail_transactions");
        Schema::dropIfExists("purchase_transactions");
        Schema::dropIfExists("purchase_detail_transactions");
        Schema::dropIfExists("receivables");
        Schema::dropIfExists("payables");
    }
};
