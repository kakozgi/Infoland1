<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepuestosTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repuestos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255); // Nombre del repuesto
            $table->integer('contador_vida_util'); // Contador de vida útil del repuesto
            $table->timestamps(); // Crea los campos 'created_at' y 'updated_at'
        });
    }

    /**
     * Deshacer las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repuestos');
    }
}
