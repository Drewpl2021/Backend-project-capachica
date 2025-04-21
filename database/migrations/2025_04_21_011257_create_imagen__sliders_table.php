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
        Schema::create('imagen__sliders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('slider_id');
            $table->string('url_image');
            $table->boolean('estado');
            $table->integer('codigo');
            $table->timestamps();
            $table->foreign('slider_id')->references('id')->on('slider__munis')->onDelete('cascade'); // Corrigido aquí
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagen__sliders');
    }
};
