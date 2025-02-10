<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('style')->nullable(); // 8-bit, Nintendo, etc.
            $table->integer('duration'); // in seconds
            $table->integer('happiness_level')->nullable();
            $table->integer('energy_level')->nullable();
            $table->string('status'); // processing, completed, failed
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generations');
    }
};
