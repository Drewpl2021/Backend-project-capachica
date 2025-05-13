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
            // Definir columna UUID para la clave primaria
            $table->uuid('id')->primary();

            // Definir otras columnas del emprendedor
            $table->string('razon_social');
            $table->string('address');
            $table->uuid('user_id');  // Definir el UUID para el user_id
            $table->string('code');
            $table->string('ruc');
            $table->text('description')->nullable();
            $table->string('lugar')->nullable();
            $table->string('img_logo')->nullable();
            $table->string('name_family');
            $table->uuid('asociacion_id');  // Definir el UUID para el asociacion_id

            // Crear la relación con la tabla 'asociacions'
            $table->foreign('asociacion_id')
                ->references('id')
                ->on('asociacions')
                ->onDelete('cascade');

            // Crear la relación con la tabla 'users'
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
