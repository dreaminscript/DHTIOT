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
        Schema::create('lamps', function (Blueprint $table) {
            $table->id();
            $table->enum('lamp1', ['on','off'])->default('off');
            $table->enum('lamp2', ['on','off'])->default('off');
            $table->enum('lamp3', ['on','off'])->default('off');
            $table->enum('lamp4', ['on','off'])->default('off');
            $table->enum('lamp5', ['on','off'])->default('off');
            $table->enum('lamp6', ['on','off'])->default('off');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lamps');
    }
};