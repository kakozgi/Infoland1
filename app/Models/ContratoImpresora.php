<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Orchid\Attachment\Attachable;


class ContratoImpresora extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    // Definir la tabla asociada al modelo
    protected $table = 'contratosImpresoras';

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'contrato_id',
        'impresora_id',
        'copias_minimas',
    ];

    // Relación con el modelo Contrato
    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    public function impresora()
    {
        return $this->belongsTo(Impresora::class);
    }
}
