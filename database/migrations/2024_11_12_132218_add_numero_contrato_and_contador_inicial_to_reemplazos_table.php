<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumeroContratoAndContadorInicialToReemplazosTable extends Migration
{
    public function up()
    {
        Schema::table('reemplazos', function (Blueprint $table) {
            $table->string('numero_contrato')->nullable();
            $table->integer('contador_inicial')->default(0);
        });
    }

    public function down()
    {
        Schema::table('reemplazos', function (Blueprint $table) {
            $table->dropColumn(['numero_contrato', 'contador_inicial']);
        });
    }
}
