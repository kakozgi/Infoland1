<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReemplazosTableAddContadores extends Migration
{
    public function up()
    {
        Schema::table('reemplazos', function (Blueprint $table) {
            $table->integer('contador_final')->nullable()->after('contador_inicial')->comment('Contador final para cerrar el contrato');
        });
    }

    public function down()
    {
        Schema::table('reemplazos', function (Blueprint $table) {
            $table->dropColumn(['contador_inicial', 'contador_final']);
        });
    }
}
