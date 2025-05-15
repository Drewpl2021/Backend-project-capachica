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
        Schema::create('emprendedor_service', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->boolean('status')->default(true);
            $table->foreignUuid('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignUuid('emprendedor_id')->constrained('emprendedors')->onDelete('cascade');

            $table->integer('cantidad')->nullable();
            $table->string('name');
            $table->string('description');
            $table->decimal('costo', 10, 2);
            $table->decimal('costo_unidad', 10, 2)->nullable();
            $table->string('code')->unique();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emprendedor_service');
    }
};
