<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Orchid\Attachment\Attachable;
use Carbon\Carbon;

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
    public function reemplazos()
    {
        return $this->hasMany(Reemplazo::class, 'id_impresora_original');
    }

    // Relación con Historial de Contadores
    public function historialContadores()
    {
        return $this->hasMany(HistorialContador::class);
    }

    // Obtener el último historial de contador del mes anterior o el primero del mes actual
    public function ultimoHistorial()
    {
        $fechaActual = now();
        $mesAnterior = $fechaActual->copy()->subMonth();

        // Buscar el último historial del mes anterior
        $ultimoHistorialMesAnterior = $this->historialContadores()
            ->whereYear('fecha_registro', $mesAnterior->year)
            ->whereMonth('fecha_registro', $mesAnterior->month)
            ->orderBy('fecha_registro', 'desc')
            ->first();

        // Si no hay historial del mes anterior, buscar el primer historial del mes actual
        if (!$ultimoHistorialMesAnterior) {
            return $this->historialContadores()
                ->whereYear('fecha_registro', $fechaActual->year)
                ->whereMonth('fecha_registro', $fechaActual->month)
                ->orderBy('fecha_registro', 'asc')
                ->first();
        }

        return $ultimoHistorialMesAnterior;
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

    // Relación con el contrato (si es aplicable)
   
}