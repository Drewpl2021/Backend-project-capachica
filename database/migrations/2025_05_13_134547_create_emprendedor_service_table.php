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
            $table->unique(['service_id', 'emprendedor_id']);

            $table->string('code')->nullable();
            $table->integer('cantidad')->default(0);
            $table->string('name');
            $table->text('description')->nullable();

            $table->foreignUuid('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignUuid('emprendedor_id')->constrained('emprendedors')->onDelete('cascade');

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
