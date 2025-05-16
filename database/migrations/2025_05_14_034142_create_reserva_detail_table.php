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
            $table->uuid('id')->primary();  // ID de tipo UUID
            $table->uuid('emprendedor_service_id');  // Clave foránea a la tabla emprendimiento_servicio
            $table->uuid('reserva_id');  // Clave foránea a la tabla reservas
            $table->string('description');  // Descripción de la reserva
            $table->float('costo');  // Costo de la reserva
            $table->float('cantidad');
            $table->float('IGV');  // Impuesto general a las ventas
            $table->float('BI');  // Base imponible
            $table->float('total');  // Total de la reserva
            $table->string('lugar');  // Lugar donde se realiza la reserva
            $table->timestamps();
            $table->softDeletes();
            // Claves foráneas
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
