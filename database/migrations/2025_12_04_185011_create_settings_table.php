<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value')->nullable();
            $table->timestamps();
        });

        // Insert Default Delivery Fee immediately
        DB::table('settings')->insert([
            ['key' => 'delivery_fee', 'value' => '49.00'],
            ['key' => 'site_name', 'value' => 'FoodHub'], // Example extra setting
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};