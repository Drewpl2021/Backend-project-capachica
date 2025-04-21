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
        Schema::create('municipalidads', function (Blueprint $table) {
            $table->uuid('id')->primary();  // Asegúrate de que este campo sea UUID
            $table->string('distrito', 100);
            $table->string('provincia', 100);
            $table->string('region', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipalidads');
    }
};
