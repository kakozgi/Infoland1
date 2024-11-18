<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;

class ImpresoraContadorLayout extends Table
{
    public $target = 'impresoras'; // Esta clave debe coincidir con lo enviado desde el query()

    public function columns(): array
    {
        return [
            TD::make('serial', 'Serial Impresora')
                ->render(function ($item) {
                    if (!isset($item['serial'])) {
                        return '<span class="text-danger">Sin datos de impresora</span>';
                    }

                    $serial = $item['serial'];
                    $esReemplazo = $item['es_reemplazo'] ?? false;
                    $originalSerial = $item['impresora_original_serial'] ?? null;

                    if ($esReemplazo && $originalSerial) {
                        return "<span class='text-info'>{$serial} (Reemplazo de: {$originalSerial})</span>";
                    }

                    return "<span class='text-success'>{$serial}</span>";
                }),
                TD::make('numero_contrato', 'Número Contrato')
                ->render(function ($item) {
                    if (isset($item['impresora']) && $item['impresora']->contrato) {
                        return $item['impresora']->contrato->numero_contrato;
                    }
            
                    return '<span class="text-warning">Sin contrato</span>';
                }),
            

            TD::make('contador_inicial', 'Último Contador')
                ->render(function ($item) {
                    return isset($item['contador_inicial']) 
                        ? number_format($item['contador_inicial']) 
                        : '<span class="text-danger">N/A</span>';

                }
            ),

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
                    return isset($item['valor_por_copia']) 
                        ? '$' . number_format($item['valor_por_copia'], 2) 
                        : '<span class="text-warning">N/A</span>';
                }),

            TD::make('copias_minimas', 'Mínimo Grupal')
                ->render(function ($item) {
                    return isset($item['copias_minimas']) 
                        ? number_format($item['copias_minimas']) 
                        : '<span class="text-warning">N/A</span>';
                }),

            TD::make('copias_minimas_individual', 'Mínimo Individual')
                ->render(function ($item) {
                    return isset($item['copias_minimas']) 
                        ? number_format($item['copias_minimas']) 
                        : '<span class="text-warning">N/A</span>';
                }),

            TD::make('total_costo', 'Costo Total')
                ->render(function ($item) {
                    $diferencia = $item['diferencia'] ?? 0;
                    $valorPorCopia = $item['valor_por_copia'] ?? 0;

                    if ($diferencia > 0) {
                        $costoTotal = $diferencia * $valorPorCopia;
                        return '$' . number_format($costoTotal, 2);
                    }

                    return '<span class="text-warning">N/A</span>';
                }),
        ];
    }
}
