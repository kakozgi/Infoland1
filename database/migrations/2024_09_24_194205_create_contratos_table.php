<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();  // id bigint, clave primaria, auto incremento
            $table->string('numero_contrato', 255);
            $table->foreignId('cliente_id')->constrained('clientes');  // Relación con clientes
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('copias_minimas');
            $table->decimal('valor_por_copia', 10, 2);
            $table->enum('tipo_minimo', ['individual', 'grupal'])->default('individual');
            $table->timestamps();  // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
