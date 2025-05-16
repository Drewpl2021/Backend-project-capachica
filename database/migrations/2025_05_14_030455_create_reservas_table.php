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
        Schema::create('reservas', function (Blueprint $table) {

            $table->uuid('id')->primary();

            // Si usas UUID para usuarios, cambia a uuid en lugar de unsignedBigInteger
            // $table->uuid('user_id');
            $table->unsignedBigInteger('user_id');

            $table->string('code')->nullable(); // código único opcional para la reserva

            $table->decimal('bi', 12, 2)->default(0);   // Base imponible (total sin impuestos)
            $table->decimal('igv', 12, 2)->default(0);  // Impuesto general a las ventas
            $table->decimal('total', 12, 2)->default(0);// Total final (bi + igv)

            $table->enum('status', ['pendiente', 'pagada', 'cancelada'])->default('pendiente'); // Estado de la reserva

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
