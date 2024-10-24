<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReemplazosTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reemplazos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_impresora_original')
                  ->constrained('impresoras') // Relación con la tabla 'impresoras'
                  ->onDelete('cascade'); // Si la impresora original se elimina, también se eliminarán los reemplazos
            $table->foreignId('id_impresora_reemplazo')
                  ->constrained('impresoras') // Relación con la tabla 'impresoras'
                  ->onDelete('cascade'); // Si la impresora de reemplazo se elimina, también se eliminarán los reemplazos
            $table->date('fecha_reemplazo');
            $table->date('fecha_reactivacion')->nullable();
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
        Schema::dropIfExists('reemplazos');
    }
}
