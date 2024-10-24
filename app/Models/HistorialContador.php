<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Orchid\Attachment\Attachable;



class HistorialContador extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    // Definir la tabla asociada al modelo (si el nombre no es plural)
    protected $table = 'historial_contadors';

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'impresora_id',
        'fecha_registro',
        'contador',
    ];

    // Relación con el modelo Impresora
    public function impresora()
    {
        return $this->belongsTo(Impresora::class, 'impresora_id');
    }
}
