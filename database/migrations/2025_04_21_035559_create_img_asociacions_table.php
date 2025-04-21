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
        Schema::create('img_asociacions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asociacion_id'); // Asegurarse de que sea uuid
            $table->string('url_image');
            $table->boolean('estado');
            $table->integer('codigo')->nullable();
            $table->timestamps();

            // Añadir la clave foránea
            $table->foreign('asociacion_id')
                ->references('id')
                ->on('asociacions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('img_asociacions');
    }
};
