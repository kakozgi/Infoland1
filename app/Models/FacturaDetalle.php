<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaDetalle extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'factura_detalles';

    // Los atributos que se pueden asignar masivamente
    protected $fillable = [
        'factura_id',
        'contrato_id',
        'impresora_id',
        'diferencia_copias',
        'copias_minimas',
        'costo_por_copia',
        'costo_calculado',
    ];

    /**
     * Relación con el modelo Factura.
     */
    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    /**
     * Relación con el modelo Contrato.
     */
    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    /**
     * Relación con el modelo Impresora.
     */
    public function impresora()
    {
        return $this->belongsTo(Impresora::class);
    }
}
