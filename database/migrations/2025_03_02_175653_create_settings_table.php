<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            ['key' => 'print_module', 'value' => 'off'],
            ['key' => 'payment_module', 'value' => 'off'],
            ['key' => 'smtp_module', 'value' => 'off'],
            ['key' => 'price_module', 'value' => 'off']
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('settings');
    }
};
