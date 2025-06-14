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
            $table->uuid('municipalidad_id');
            $table->string('nombre');
            $table->string('descripcion', 1000); // antes podía ser 255
            $table->string('url');
            $table->string('lugar');
            $table->boolean('estado');
            $table->foreign('municipalidad_id')
                ->references('id')
                ->on('municipalidads')
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
        Schema::dropIfExists('asociacions');
    }
};
