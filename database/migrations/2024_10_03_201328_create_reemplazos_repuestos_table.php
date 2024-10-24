<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReemplazosRepuestosTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reemplazosRepuestos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_impresora'); // Relación con la tabla impresoras
            $table->unsignedBigInteger('id_repuesto'); // Relación con la tabla repuestos
            $table->integer('contador_inicial'); // Contador inicial
            $table->date('fecha_instalacion'); // Fecha de instalación del repuesto
            $table->timestamps(); // Crea los campos 'created_at' y 'updated_at'

            // Definir las claves foráneas
            $table->foreign('id_impresora')->references('id')->on('impresoras')->onDelete('cascade');
            $table->foreign('id_repuesto')->references('id')->on('repuestos')->onDelete('cascade');
        });
    }

    /**
     * Deshacer las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reemplazosRepuestos');
    }
}
