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
        Schema::create('slider__munis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('municipio_descrip_id'); // Cambié el nombre de la tabla referenciada aquí
            $table->string('titulo');
            $table->string('descripcion');
            $table->foreign('municipio_descrip_id')->references('id')->on('municipalidad__descripcions')->onDelete('cascade'); // Corrigido aquí
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slider__munis');
    }
};
