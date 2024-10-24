<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id(); // ID auto-incrementable
            $table->foreignId('contrato_id')->constrained('contratos')->onDelete('cascade'); // Relación con la tabla contratos
            $table->date('fecha_factura'); // Fecha de la factura
            $table->decimal('valor_total', 10, 2); // Valor total de la factura
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Revertir las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturas');
    }
}
