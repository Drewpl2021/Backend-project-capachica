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
        Schema::create('emprendedors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('razon_social');
            $table->string('familia');
            $table->uuid('asociacion_id'); // Cambiado a uuid
            $table->foreign('asociacion_id') // Clave foránea
                ->references('id')
                ->on('asociacions')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emprendedors');
    }
};
