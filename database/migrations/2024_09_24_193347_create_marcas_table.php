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
        Schema::create('marcas', function (Blueprint $table) {
            $table->id();  // id bigint, clave primaria, auto incremento
            $table->string('nombre', 255);  // nombre varchar(255)
            $table->string('descripcion', 255);  // descripción varchar(255)
            $table->timestamps();  // campos created_at y updated_at
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('marcas');
    }
};
