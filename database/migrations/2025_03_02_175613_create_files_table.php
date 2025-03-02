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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->integer('number_of_pages')->nullable();
            $table->integer('copies')->default(1);
            $table->enum('impression', ['color', 'black_white', 'mixed']);
            $table->enum('orientation', ['portrait', 'landscape']);
            $table->enum('paper_size', ['A4', 'A3']);
            $table->boolean('front_back')->default(false);
            $table->boolean('binding')->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
