<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Orchid\Attachment\Attachable;


class Repuesto extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    // Definir la tabla asociada al modelo (si el nombre no es plural)
    protected $table = 'repuestos';

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre',
        'contador_vida_util',
    ];

    // Relación con la tabla 'reemplazos_repuestos' (para obtener los repuestos usados en los reemplazos de impresoras)
    public function reemplazosRepuestos()
    {
        return $this->hasMany(ReemplazoRepuesto::class, 'id_repuesto');
    }
}
