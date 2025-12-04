<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. ORDERS TABLE
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, completed, cancelled
            $table->string('payment_method'); // cod, gcash
            $table->string('delivery_type'); // delivery, pickup
            $table->decimal('total_amount', 10, 2);
            $table->text('address')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();
        });

        // 2. ORDER ITEMS TABLE (Links specific food to an order)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('menu_item_id')->constrained('menu_items');
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Price at the time of purchase
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};