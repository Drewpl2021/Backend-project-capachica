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
        Schema::create('emprendedors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('razon_social')->nullable();
            $table->string('address')->nullable();
            $table->string('code')->nullable();
            $table->string('ruc')->nullable();
            $table->text('description')->nullable();
            $table->string('lugar')->nullable();
            $table->string('img_logo')->nullable();
            $table->string('name_family')->nullable();
            $table->boolean('status')->default(true);
            $table->uuid('asociacion_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('asociacion_id')->references('id')->on('asociacions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emprendedors', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }
};
