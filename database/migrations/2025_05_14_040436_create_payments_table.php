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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();  // ID de tipo UUID
            $table->string('code');  // Código del pago
            $table->string('codigo_pago_yape')->nullable();  // Nuevo campo para almacenar el código de pago de Yape
            $table->decimal('total', 10, 2);  // Total de pago
            $table->decimal('bi', 10, 2);  // Base imponible
            $table->decimal('igv', 10, 2);  // Impuesto general a las ventas
            $table->timestamps();  // Timestamps: created_at y updated_at
            $table->softDeletes();  // Soft delete: para eliminar lógicamente el registro
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
