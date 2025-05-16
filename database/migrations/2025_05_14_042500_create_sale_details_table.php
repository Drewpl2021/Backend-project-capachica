<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->uuid('id')->primary();  // ID de tipo UUID
            $table->uuid('emprendedor_service_id');  // Clave foránea a la tabla emprendimiento_servicio
            $table->uuid('sale_id');  // Clave foránea a la tabla sale
            $table->string('description');  // Descripción del detalle
            $table->decimal('costo');  // Costo del detalle
            $table->decimal('IGV');  // Impuesto general a las ventas
            $table->decimal('BI');  // Base imponible
            $table->decimal('total');  // Total del detalle
            $table->string('lugar');  // Lugar donde se realiza la venta
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('emprendedor_service_id')->references('id')->on('emprendedor_service')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
