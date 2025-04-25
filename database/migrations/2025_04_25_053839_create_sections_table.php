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
        Schema::create('sections', function (Blueprint $table) {
            $table->uuid('id')->primary();  // Usamos UUID como llave primaria
            $table->string('code');         // Atributo 'Code' como string
            $table->string('name');         // Atributo 'Name' como string
            $table->boolean('status')->default(true); // Cambiado a booleano
            $table->timestamps();           // Incluye 'created_at' y 'updated_at'
            $table->softDeletes();          // Para manejar 'deleted_at' (soft delete)
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->enum('status', ['true', 'false'])->change(); // Si es necesario revertir, usamos enum
        });
    }
};
