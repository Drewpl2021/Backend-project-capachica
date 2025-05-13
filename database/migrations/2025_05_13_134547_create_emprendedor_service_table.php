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
            $table->uuid('service_id');
            $table->uuid('emprendedor_id');
            $table->string('code')->nullable();
            $table->integer('cantidad')->default(0);
            $table->string('name');
            $table->text('description')->nullable();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('emprendedor_id')->references('id')->on('emprendedors')->onDelete('cascade');

            $table->timestamps();

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
