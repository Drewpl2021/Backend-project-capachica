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
        Schema::create('img_asociacions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('url_image');
            $table->boolean('status');
            $table->integer('codigo');
            $table->foreignId('asociacion_id')->constrained('asociacions'); // Clave foránea
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('img_asociacions');
    }
};
