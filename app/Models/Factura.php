<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Orchid\Attachment\Attachable;

class Factura extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    // Definir la tabla asociada al modelo (si el nombre no es plural)
    protected $table = 'facturas';

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'contrato_id',
        'fecha_factura',
        'valor_total',
    ];

    // Relación con el modelo Contrato
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }
}
