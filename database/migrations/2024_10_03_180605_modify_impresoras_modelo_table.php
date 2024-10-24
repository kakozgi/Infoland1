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
        Schema::table('impresoras', function (Blueprint $table) {
            $table->dropForeign(['id_modelo']);  // Elimina la clave foránea existente
            $table->foreign('id_modelo')->references('id')->on('modelo_impresoras');  // Crea la nueva clave foránea
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('impresoras', function (Blueprint $table) {
            $table->dropForeign(['id_modelo']);  // Revierte la nueva clave foránea
            $table->foreign('id_modelo')->references('id')->on('modelos_impresoras');  // Restaura la clave foránea original
        });
    }
};
