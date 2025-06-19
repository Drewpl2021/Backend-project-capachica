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
            $table->uuid('id')->primary();
            $table->string('code');
            $table->string('codigo_pago_yape')->nullable();
            $table->decimal('total', 10, 2);
            $table->decimal('bi', 10, 2);
            $table->decimal('igv', 10, 2);
            $table->timestamps();
            $table->softDeletes();
            $table->foreignUuid('reserva_id')
                ->constrained('reservas')
                ->onDelete('cascade');
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
