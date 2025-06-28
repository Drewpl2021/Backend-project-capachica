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
        // Tabla principal de paquetes
        Schema::create('paquetes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('emprendedor_id')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('precio', 10, 2);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('emprendedor_id')->references('id')->on('emprendedors')->onDelete('cascade');
        });

        // Tabla pivote para productos en paquetes
        Schema::create('paquete_emprendedor_service', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('paquete_id')->index();
            $table->uuid('emprendedor_service_id')->index();
            $table->integer('cantidad')->default(1);
            $table->timestamps();
            $table->foreign('paquete_id')->references('id')->on('paquetes')->onDelete('cascade');
            $table->foreign('emprendedor_service_id')->references('id')->on('emprendedor_service')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paquete_emprendedor_service');
        Schema::dropIfExists('paquetes');
    }
};
