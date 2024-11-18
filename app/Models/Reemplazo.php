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

    protected $table = 'reemplazos';

    protected $fillable = [
        'id_impresora_original',
        'id_impresora_reemplazo',
        'fecha_reemplazo',
        'fecha_reactivacion',
        'numero_contrato',       // Nuevo campo para el contrato asociado
        'contador_inicial', // Nuevo campo para el contador inicial
        'contador_final',  
    ];

    // Relación hacia la impresora original
    public function impresoraOriginal()
    {
        return $this->belongsTo(Impresora::class, 'id_impresora_original');
    }

    // Relación hacia la impresora de reemplazo
    public function impresoraReemplazo()
    {
        return $this->belongsTo(Impresora::class, 'id_impresora_reemplazo');
    }
}
