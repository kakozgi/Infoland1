<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturaDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('factura_detalles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('factura_id')->constrained('facturas')->onDelete('cascade');
            $table->foreignId('contrato_id')->constrained('contratos')->onDelete('cascade');
            $table->foreignId('impresora_id')->constrained('impresoras')->onDelete('cascade');
            $table->integer('diferencia_copias');
            $table->integer('copias_minimas');
            $table->decimal('costo_por_copia', 10, 2);
            $table->decimal('costo_calculado', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factura_detalles');
    }
}
