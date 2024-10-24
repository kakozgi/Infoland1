<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Orchid\Attachment\Attachable;

class ModeloImpresora extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    protected $fillable = [
        'nombre',
        'id_marca',
        'descripcion',
    ];

    // Relación con Marca (Un modelo de impresora pertenece a una marca)
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_marca')->withDefault();
    }

    // Relación con Impresoras (Un modelo de impresora tiene muchas impresoras)
    public function impresoras()
    {
        return $this->hasMany(Impresora::class, 'id_modelo');
    }
}
