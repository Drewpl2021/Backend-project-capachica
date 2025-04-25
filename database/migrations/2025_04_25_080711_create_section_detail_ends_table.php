<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('section_detail_ends', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Usamos UUID como llave primaria
            $table->boolean('status')->default(true); // Campo de estado (booleano)
            $table->string('code'); // Código
            $table->string('image'); // Imagen
            $table->string('title'); // Título
            $table->text('description'); // Descripción
            $table->string('subtitle'); // Subtítulo
            $table->uuid('section_detail_id'); // FK que referencia a `sections_details`
            $table->timestamps(); // created_at y updated_at
            $table->softDeletes(); // Para manejar 'deleted_at' (soft delete)

            // Definimos la clave foránea para la relación con `sections_details`
            $table->foreign('section_detail_id')->references('id')->on('section_details')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('section_detail_ends');
    }
};
