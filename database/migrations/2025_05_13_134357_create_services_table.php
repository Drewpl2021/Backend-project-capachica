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
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');               // Nombre del servicio
            $table->text('description')->nullable(); // Descripción detallada
            $table->string('code')->unique(); // Código único para identificar el servicio
            $table->string('category')->nullable();  // Categoría o tipo del servicio (ej. Turismo, Hospedaje)
            $table->boolean('status')->default(true); // Estado activo/inactivo para control
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
