<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratosImpresorasTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratosImpresoras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contrato_id'); // Relación con la tabla contratos
            $table->unsignedBigInteger('impresora_id'); // Relación con la tabla impresoras
            $table->integer('copias_minimas'); // Copias mínimas definidas para el contrato
            $table->timestamps(); // Crea los campos 'created_at' y 'updated_at'

            // Definir las claves foráneas
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('impresora_id')->references('id')->on('impresoras')->onDelete('cascade');
        });
    }

    /**
     * Deshacer las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratosImpresoras');
    }
}
