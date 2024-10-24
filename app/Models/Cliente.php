<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Orchid\Attachment\Attachable;


class Cliente extends Model
{

    use HasFactory, AsSource, Filterable, Attachable;
    protected $fillable = [
        'nombre',
        'rut',
        'correo',
        'telefono',
        'direccion',
    ];

    // Relación con Contratos (Un cliente tiene muchos contratos)
    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

}