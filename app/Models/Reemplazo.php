<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Orchid\Attachment\Attachable;

class Reemplazo extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    // Definir la tabla asociada al modelo (si el nombre no es plural)
    protected $table = 'reemplazos';

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'id_impresora_original',
        'id_impresora_reemplazo',
        'fecha_reemplazo',
        'fecha_reactivacion',
    ];

    // Relación con el modelo Impresora (impresora original)
    public function impresoraOriginal()
    {
        return $this->belongsTo(Impresora::class, 'id_impresora_original');
    }

    // Relación con el modelo Impresora (impresora de reemplazo)
    public function impresoraReemplazo()
    {
        return $this->belongsTo(Impresora::class, 'id_impresora_reemplazo');
    }
}
