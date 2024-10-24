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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();  // id bigint, clave primaria, auto incremento
            $table->string('nombre', 255);  // nombre varchar(255)
            $table->string('rut', 255)->unique();  // rut varchar(255), campo único
            $table->string('correo', 255);  // correo varchar(255)
            $table->string('telefono', 255)->unique();  // teléfono varchar(255), campo único
            $table->string('direccion', 255);  // dirección varchar(255)
            $table->timestamps();  // campos created_at y updated_at
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
