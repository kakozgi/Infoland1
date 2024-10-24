<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Orchid\Attachment\Attachable;

class Marca extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relación con ModelosImpresoras (Una marca tiene muchos modelos de impresoras)
    public function modelosImpresoras()
    {
        return $this->hasMany(ModeloImpresora::class);
    }
}