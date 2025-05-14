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
            $table->uuid('id')->primary();
            $table->string('razon_social');
            $table->string('address');
            $table->string('code');
            $table->string('ruc');
            $table->text('description')->nullable();
            $table->string('lugar')->nullable();
            $table->string('img_logo')->nullable();
            $table->string('name_family');
            $table->uuid('asociacion_id');  // Relación con la tabla 'asociacions'
            $table->timestamps();
            $table->softDeletes();
            // Relación con la tabla 'asociacions'
            $table->foreign('asociacion_id')->references('id')->on('asociacions')->onDelete('cascade');
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
