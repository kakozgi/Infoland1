<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratos', function (Blueprint $table) {
            // Modificar el campo tipo_minimo para agregar el nuevo tipo
            $table->enum('tipo_minimo', ['individual', 'grupal', 'directo'])->default('individual')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratos', function (Blueprint $table) {
            // Revertir el cambio eliminando 'nuevo_tipo' si haces rollback
            $table->enum('tipo_minimo', ['individual', 'grupal'])->default('individual')->change();
        });
    }
}

