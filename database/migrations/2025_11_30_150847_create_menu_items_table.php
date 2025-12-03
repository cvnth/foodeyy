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
        Schema::create('menu_items', function (Blueprint $table) {
    $table->id();

    $table->foreignId('category_id')->constrained()->onDelete('cascade');

    $table->string('name');
    $table->text('description')->nullable();

    $table->string('image_url')->nullable();

    $table->decimal('price', 8, 2);
    $table->decimal('original_price', 8, 2)->nullable();

    $table->integer('preparation_time')->default(15);

    $table->integer('calories')->default(0);

    $table->boolean('is_featured')->default(false);
    $table->boolean('is_available')->default(true);

    $table->json('tags')->nullable();

    $table->decimal('rating', 3, 2)->default(0.00);
    $table->integer('review_count')->default(0);

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
