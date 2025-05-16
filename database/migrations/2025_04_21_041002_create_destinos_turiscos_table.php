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
        Schema::create('destinos_turiscos', function (Blueprint $table) {
            $table->uuid('id')->primary();  // Asegúrate de que sea un uuid
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('lugar');
            $table->uuid('emprendedor_id'); // Cambiado a uuid
            $table->foreign('emprendedor_id')
                ->references('id')
                ->on('emprendedors')
                ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinos_turiscos');
    }
};
