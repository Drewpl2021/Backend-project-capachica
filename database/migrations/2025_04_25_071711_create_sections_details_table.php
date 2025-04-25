<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('section_details', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Usamos UUID como llave primaria
            $table->boolean('status')->default(true);  // Campo de estado (booleano)
            $table->string('code');  // Código
            $table->string('title');  // Título
            $table->text('description');  // Descripción
            $table->uuid('section_id');  // FK de relación con la tabla sections
            $table->timestamps();  // created_at y updated_at
            $table->softDeletes();  // Para manejar 'deleted_at' (soft delete)

            // Definimos la clave foránea para la relación con la tabla sections
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('section_details');
    }

};
