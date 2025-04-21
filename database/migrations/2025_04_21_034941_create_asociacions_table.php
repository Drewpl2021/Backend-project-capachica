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
        Schema::create('asociacions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('municipalidad_id'); // Clave foránea que hace referencia a la tabla 'municipalidads'
            $table->string('nombres');
            $table->string('descripcion');
            $table->string('lugar');
            $table->boolean('status');
            $table->foreign('municipalidad_id')
                ->references('id')
                ->on('municipalidads')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asociacions');
    }
};
