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
        Schema::create('municipalidad__descripcions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('municipalidad_id');
            $table->string('logo');
            $table->string('direccion');
            $table->text('descripcion');
            $table->string('ruc');
            $table->string('correo');
            $table->string('nombre_alcalde');
            $table->string('anio_gestion');
            $table->timestamps();
            $table->foreign('municipalidad_id')
                ->references('id')
                ->on('municipalidads')
                ->onDelete('cascade');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipalidad__descripcions');
    }
};
