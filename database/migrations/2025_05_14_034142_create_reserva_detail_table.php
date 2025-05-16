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
        Schema::create('reserve_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();  // ID UUID único

            $table->uuid('emprendedor_service_id');  // FK a emprendedor_service
            $table->uuid('reserva_id');  // FK a reservas

            $table->decimal('costo', 12, 2);  // Costo unitario o total (según definición)
            $table->decimal('cantidad', 12, 2); // Mejor decimal para cantidades
            $table->decimal('IGV', 12, 2);  // Impuesto general a las ventas
            $table->decimal('BI', 12, 2);  // Base imponible (subtotal sin impuestos)
            $table->decimal('total', 12, 2);  // Total final de este detalle (BI + IGV)

            $table->string('lugar')->nullable();// Lugar (si es opcional, hazlo nullable)
            $table->string('description')->nullable(); // Description (si es opcional, hazlo nullable)

            $table->timestamps();
            $table->softDeletes();

            // Llaves foráneas con cascada
            $table->foreign('emprendedor_service_id')->references('id')->on('emprendedor_service')->onDelete('cascade');
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reserve_detail');
    }
};
