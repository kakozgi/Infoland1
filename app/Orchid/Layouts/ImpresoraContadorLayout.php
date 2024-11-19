<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;
use App\Models\Contrato;
use App\Models\Impresora;

class ImpresoraContadorLayout extends Table
{
    public $target = 'impresoras'; // Esta clave debe coincidir con lo enviado desde el query()

    public function columns(): array
    {
        return [
            TD::make('serial', 'Serial Impresora')
                ->render(function ($item) {
                    $serial = $item['serial'] ?? 'Sin datos de impresora';
                    $esReemplazo = $item['es_reemplazo'] ?? false;
                    $originalSerial = $item['impresora_original_serial'] ?? null;

                    if ($esReemplazo && $originalSerial) {
                        return "<span class='text-info'>{$serial} (Reemplazo de: {$originalSerial})</span>";
                    }

                    return "<span class='text-success'>{$serial}</span>";
                }),

                TD::make('numero_contrato', 'Número Contrato')
                ->render(function ($impresora) {
                    if (is_object($impresora) && $impresora->contrato) {
                        return $impresora->contrato->numero_contrato;
                    }
            
                    return '<span class="text-warning">Sin contrato</span>';
                }),
            

            TD::make('contador_inicial', 'Último Contador')
                ->render(function ($item) {
                    return isset($item['contador_inicial']) 
                        ? number_format($item['contador_inicial']) 
                        : '<span class="text-danger">N/A</span>';
                }),

            TD::make('contador_final', 'Contador Actual')
                ->render(function ($item) {
                    return isset($item['contador_final']) 
                        ? number_format($item['contador_final']) 
                        : '<span class="text-danger">N/A</span>';
                }),

            TD::make('diferencia', 'Diferencia')
                ->render(function ($item) {
                    return isset($item['diferencia']) 
                        ? number_format($item['diferencia']) 
                        : '<span class="text-danger">0</span>';
                }),

            TD::make('valor_por_copia', 'Valor Copia')
                ->render(function ($item) {
                    if (!isset($item['id'])) {
                        return '<span class="text-warning">N/A</span>';
                    }

                    // Verificar el contrato directamente desde la base de datos
                    $impresora = Impresora::find($item['id']);
                    if ($impresora && $impresora->contrato) {
                        return '$' . number_format($impresora->contrato->valor_por_copia, 2);
                    }

                    return '<span class="text-warning">N/A</span>';
                }),

            TD::make('copias_minimas', 'Mínimo Grupal')
                ->render(function ($item) {
                    if (!isset($item['id'])) {
                        return '<span class="text-warning">N/A</span>';
                    }

                    // Verificar el contrato directamente desde la base de datos
                    $impresora = Impresora::find($item['id']);
                    if ($impresora && $impresora->contrato) {
                        return number_format($impresora->contrato->copias_minimas);
                    }

                    return '<span class="text-warning">N/A</span>';
                }),

            TD::make('copias_minimas_individual', 'Mínimo Individual')
                ->render(function ($item) {
                    if (!isset($item['id'])) {
                        return '<span class="text-warning">N/A</span>';
                    }

                    // Verificar el contrato de la impresora directamente
                    $impresora = Impresora::find($item['id']);
                    if ($impresora && $impresora->contratoImpresora) {
                        return number_format($impresora->contratoImpresora->copias_minimas);
                    }

                    return '<span class="text-warning">N/A</span>';
                }),

            TD::make('total_costo', 'Costo Total')
                ->render(function ($item) {
                    $diferencia = $item['diferencia'] ?? 0;

                    if (!isset($item['id'])) {
                        return '<span class="text-warning">N/A</span>';
                    }

                    $impresora = Impresora::find($item['id']);
                    if ($impresora && $impresora->contrato) {
                        $valorPorCopia = $impresora->contrato->valor_por_copia ?? 0;
                        $costoTotal = $diferencia * $valorPorCopia;
                        return '$' . number_format($costoTotal, 2);
                    }

                    return '<span class="text-warning">N/A</span>';
                }),
        ];
    }
}
