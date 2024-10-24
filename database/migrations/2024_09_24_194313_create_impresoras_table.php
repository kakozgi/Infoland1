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
        Schema::create('impresoras', function (Blueprint $table) {
            $table->id();  // id bigint, clave primaria, auto incremento
            $table->string('serial', 50)->unique();
            $table->foreignId('id_modelo')->constrained('modelos_impresoras');  // Relación con modelos_impresoras
            $table->enum('estado', ['contrato', 'disponible', 'servicio_tecnico', 'desarme', 'recambio'])->default('disponible');
            $table->foreignId('contrato_id')->nullable()->constrained('contratos');  // Relación opcional con contratos
            $table->string('ubicacion', 255);
            $table->string('telefono', 255);
            $table->integer('contador_actual')->default(0);
            $table->timestamps();  // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('impresoras');
    }
};
