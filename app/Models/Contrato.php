<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Orchid\Attachment\Attachable;
use App\Models\Cliente;
use App\Models\Impresora;


class Contrato extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    // Definir la tabla si no sigue el nombre en plural por convención
    protected $table = 'contratos';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'numero_contrato',
        'cliente_id',
        'fecha_inicio',
        'fecha_fin',
        'copias_minimas',
        'valor_por_copia',
        'tipo_minimo',
    ];

    // Indicar que la clave primaria es un bigint
    protected $primaryKey = 'id';

    // Deshabilitar los timestamps si no se usan
    public $timestamps = true;

    // Relación con la tabla `clientes` (Un contrato pertenece a un cliente)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Relación con la tabla `impresoras` (Un contrato puede tener muchas impresoras)
    public function impresoras()
    {
        return $this->hasMany(Impresora::class, 'contrato_id');
    }

    // Relación con la tabla `facturas` (Un contrato tiene muchas facturas)
    public function facturas()
    {
        return $this->hasMany(Factura::class, 'contrato_id');
    }

    // Relación con la tabla `contratos_impresoras` (Contrato tiene impresoras asociadas a través de la tabla intermedia)
    public function contratosImpresoras()
    {
        return $this->hasMany(ContratosImpresora::class, 'contrato_id');
    }

    public function scopeIndividual($query)
    {
        return $query->where('tipo_minimo', 'individual');
    }

    public function calcularDiferenciaContrato()
{
    $diferenciaContrato = 0;

    foreach ($this->impresoras as $impresora) {
        $diferenciaContrato += $impresora->calcularDiferenciaTotal();
    }

    return $diferenciaContrato;
}

}
