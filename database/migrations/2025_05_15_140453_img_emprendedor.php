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
        Schema::create('img_emprendedores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('emprendedor_id'); // Cambiado a uuid
            $table->string('url_image');
            $table->text('description')->nullable(); // Descripción detallada
            $table->boolean('estado');
            $table->string('code');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('emprendedor_id')
                ->references('id')
                ->on('emprendedors')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('img_emprendedores');
    }
};
