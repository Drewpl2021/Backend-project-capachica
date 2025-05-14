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
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('emprendedor_id');
            $table->uuid('payment_id');
            $table->uuid('reserva_id');
            $table->string('code');
            $table->float('IGV');
            $table->float('BI');
            $table->float('total');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('emprendedor_id')->references('id')->on('emprendedors')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
