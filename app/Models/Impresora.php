<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Orchid\Attachment\Attachable;
use Carbon\Carbon;
use App\Models\Contrato;
use App\Models\Reemplazo;
use App\Models\HistorialContador;
use App\Models\ModeloImpresora;
use App\Models\Cliente;
use App\Models\ContratoImpresora;


class Impresora extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    protected $fillable = [
        'serial',
        'id_modelo',
        'estado',
        'contrato_id',
        'ubicacion',
        'telefono',
        'contador_actual',
    ];

    // Relación con ModeloImpresora (Una impresora pertenece a un modelo)
    public function modeloImpresora()
    {
        return $this->belongsTo(ModeloImpresora::class, 'id_modelo');
    }

    // Relación con Contrato (Una impresora puede estar asociada a un contrato)
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    // Relación con Cliente a través del contrato
    public function cliente()
    {
        return $this->hasOneThrough(Cliente::class, Contrato::class, 'id', 'id', 'contrato_id', 'cliente_id');
    }

    // Relación con Reemplazos (Una impresora puede tener varios reemplazos)
    public function reemplazo()
    {
        return $this->hasOne(Reemplazo::class, 'id_impresora_original');
    }
    
    // Relación inversa para obtener la impresora original de una impresora de reemplazo
    public function reemplazadaPor()
    {
        return $this->hasOne(Reemplazo::class, 'id_impresora_reemplazo');
    }
    
    // Relación con Historial de Contadores
    public function historialContadores()
    {
        return $this->hasMany(HistorialContador::class);
    }

    // public function ultimoHistorial()
    // {
    //     //dd('Método ultimoHistorial ejecutado'); // Verificar que el método se está ejecutando
    
    //     $fechaReferencia = now()->subMonth(); // Comienza un mes antes del actual
    
    //     // Retrocede mes a mes para encontrar el historial más reciente antes del mes actual
    //     while ($fechaReferencia->year >= 2000) {
    //         $ultimoHistorialMesAnterior = $this->historialContadores()
    //             ->whereYear('fecha_registro', $fechaReferencia->year)
    //             ->whereMonth('fecha_registro', $fechaReferencia->month)
    //             ->orderBy('fecha_registro', 'desc')
    //             ->orderBy('created_at', 'desc') // Orden adicional para asegurar el último del día
    //             ->first();
    
    //         // Verificar la búsqueda en el mes actual de la iteración
    //         // dd([
    //         //     'Iterando Mes' => $fechaReferencia->format('Y-m'),
    //         //     'Último Historial Encontrado en el Mes' => $ultimoHistorialMesAnterior,
    //         // ]);
    
    //         // Si encuentra un historial en el mes actual de búsqueda, devolver ese último historial
    //         if ($ultimoHistorialMesAnterior) {
    //             // dd([
    //             //     'Tipo de búsqueda' => 'Mes Anterior',
    //             //     'Fecha de Referencia' => $fechaReferencia->format('Y-m'),
    //             //     'Último Historial Encontrado' => $ultimoHistorialMesAnterior,
    //             //     'Fecha Created_at' => $ultimoHistorialMesAnterior->created_at,
    //             //     'Contador' => $ultimoHistorialMesAnterior->contador,
    //             // ]);
    //             return $ultimoHistorialMesAnterior;
    //         }
    
    //         // Retrocede un mes si no se encuentra historial en el mes actual de búsqueda
    //         $fechaReferencia->subMonth();
    //     }
    
    //     // Si no encuentra historial en meses anteriores, busca el último registro del mes actual
    //     $primerRegistroDelMes = $this->historialContadores()
    //         ->whereYear('fecha_registro', now()->year)
    //         ->whereMonth('fecha_registro', now()->month)
    //         ->orderBy('fecha_registro', 'asc')
    //         ->orderBy('created_at', 'asc')
    //         ->first();
    
    //     $ultimoRegistroDelMes = $this->historialContadores()
    //         ->whereYear('fecha_registro', now()->year)
    //         ->whereMonth('fecha_registro', now()->month)
    //         ->orderBy('fecha_registro', 'desc')
    //         ->orderBy('created_at', 'desc')
    //         ->first();
    
    //     if ($primerRegistroDelMes && $ultimoRegistroDelMes) {
    //         // dd([
    //         //     'Tipo de búsqueda' => 'Mes Actual',
    //         //     'Primer Registro del Mes Actual' => $primerRegistroDelMes,
    //         //     'Fecha Created_at del Primer Registro' => $primerRegistroDelMes->created_at,
    //         //     'Último Registro del Mes Actual' => $ultimoRegistroDelMes,
    //         //     'Fecha Created_at del Último Registro' => $ultimoRegistroDelMes->created_at,
    //         //     'Contador del Último Registro' => $ultimoRegistroDelMes->contador,
    //         // ]);
    //     }
    
    //     // Si no se ha encontrado ningún historial en absoluto
    //     //dd('No se encontró ningún historial en el mes actual ni en meses anteriores');
    //     return null;
    // }
    
    public function ultimoHistorial()
{
    $fechaReferencia = now()->subMonth(); // Comienza un mes antes del actual

    // Retrocede mes a mes para encontrar el historial más reciente antes del mes actual
    while ($fechaReferencia->year >= 2000) {
        $ultimoHistorialMesAnterior = $this->historialContadores()
            ->whereYear('fecha_registro', $fechaReferencia->year)
            ->whereMonth('fecha_registro', $fechaReferencia->month)
            ->orderBy('fecha_registro', 'desc')
            ->orderBy('created_at', 'desc') // Asegura el último registro dentro del mes
            ->first();

        // Si encuentra un historial en el mes actual de búsqueda, devolver ese último historial
        if ($ultimoHistorialMesAnterior) {
            return $ultimoHistorialMesAnterior;
        }

        // Retrocede un mes si no se encuentra historial en el mes actual de búsqueda
        $fechaReferencia->subMonth();
    }

    // Si no se encuentra ningún historial en meses anteriores, toma el primer registro absoluto de la impresora
    $primerHistorial = $this->historialContadores()
        ->orderBy('fecha_registro', 'asc')
        ->orderBy('created_at', 'asc')
        ->first();

    // Si existe un registro en absoluto, úsalo como último historial
    return $primerHistorial ?: null; // Retorna null si no existe ningún registro en la tabla
}

public function obtenerContadorActual()
{
    // Busca si la impresora fue reemplazada
    $reemplazo = $this->reemplazadaPor()->first();

    if ($reemplazo) {
        // Si existe reemplazo, utiliza el contador final del reemplazo
        return $reemplazo->contador_final ?? 0;
    }

    // Si no es reemplazo, usa el contador actual de la tabla impresoras
    return $this->contador_actual;
}
public function obtenerUltimoContador()
{
    // Busca si la impresora fue reemplazada
    $reemplazo = $this->reemplazadaPor()->first();

    if ($reemplazo) {
        // Si existe reemplazo, utiliza el contador inicial del reemplazo
        return $reemplazo->contador_inicial ?? 0;
    }

    // Si no es reemplazo, usa el último historial o 0
    return $this->ultimoHistorial()->contador ?? 0;
}


    // Scope para filtrar impresoras por contrato
    public function scopeDelContrato($query, $contratoId)
    {
        return $query->where('contrato_id', $contratoId);
    }

    // Relación con ContratoImpresora
    public function contratoImpresora()
    {
        return $this->hasOne(ContratoImpresora::class, 'impresora_id');
    }


    public function scopeContratadas($query)
    {
        return $query->where('estado', 'contrato');
    }

    // Scope para obtener impresoras que están disponibles
    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'disponible');
    }

   
/**
 * Obtiene el contador anterior de la impresora, considerando reemplazos y cambios de contrato.
 *
 * @return int
 */
public function obtenerContadorAnterior()
{
    $reemplazoComoOriginal = $this->reemplazo()->orderBy('fecha_reemplazo', 'desc')->first();

    if ($reemplazoComoOriginal && $reemplazoComoOriginal->numero_contrato != $this->contrato_id) {
        $contadorInicial = $reemplazoComoOriginal->contador_inicial;
        //dd("Contador inicial de reemplazo", $contadorInicial); // Agrega este dd
        return $contadorInicial;
    }

    $ultimoHistorial = $this->ultimoHistorial()->contador ?? 0;
  //  dd("Último historial", $ultimoHistorial); // Agrega este dd
    return $ultimoHistorial;
}

}