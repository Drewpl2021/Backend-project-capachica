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
        Schema::create('img_emprenpedor_service', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('emprendedor_service_id');
            $table->string('url_image');
            $table->text('description')->nullable();
            $table->boolean('estado');
            $table->string('code');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('emprendedor_service_id')
                ->references('id')
                ->on('emprendedor_service')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('img_emprenpedor_service');
    }
};
