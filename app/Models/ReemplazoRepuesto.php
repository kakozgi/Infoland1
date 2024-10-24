<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReemplazoRepuesto extends Model
{
    use HasFactory;

    // Definir la tabla asociada al modelo
    protected $table = 'reemplazosRepuestos';

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'id_impresora',
        'id_repuesto',
        'contador_inicial',
        'fecha_instalacion',
    ];

    // Relación con el modelo Impresora
    public function impresora()
    {
        return $this->belongsTo(Impresora::class, 'id_impresora');
    }

    // Relación con el modelo Repuesto
    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class, 'id_repuesto');
    }
}
