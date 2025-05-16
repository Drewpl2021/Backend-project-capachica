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
        Schema::create('emprendedor_user', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->uuid('emprendedor_id');  // Columna que hará referencia a los emprendimientos
            $table->timestamps();
            $table->softDeletes();
            // Relaciones de claves foráneas
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');  // Clave foránea con 'users'
            $table->foreign('emprendedor_id')->references('id')->on('emprendedors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emprendedor_user');
    }
};
