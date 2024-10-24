<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialContadoresTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historialContadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('impresora_id')
                  ->constrained('impresoras')
                  ->onDelete('cascade'); // Relación con la tabla 'impresoras'
            $table->date('fecha_registro');
            $table->integer('contador');
            $table->timestamps();
        });
    }

    /**
     * Deshacer las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historialContadores');
    }
}
