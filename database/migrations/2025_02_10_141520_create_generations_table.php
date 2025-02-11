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
            $table->string('style')->nullable(); // contra, nintendo, violin
            $table->float('happiness_level')->nullable(); // valence (between -1 and 1)
            $table->float('energy_level')->nullable(); // arousal (between -1 and 1)
            $table->integer('tempo')->nullable(); // tempo (between 60 and 180)
            $table->integer('velocity_min')->nullable(); // velocity_min (between 0 and 127)
            $table->integer('velocity_max')->nullable(); // velocity_max (between 0 and 127)
            $table->integer('generation_length')->nullable(); // generation_length (in seconds)
            $table->string('output_name'); // output name for the file
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