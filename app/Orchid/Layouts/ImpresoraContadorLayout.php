<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;

class ImpresoraContadorLayout extends Table
{
    public $target = 'impresoras';

    public function columns(): array
    {
        return [
            TD::make('serial', 'Serial Impresora')
                ->render(function ($item) {
                    $serial = $item['serial'] ?? '<span class="text-danger">Sin datos</span>';
                    $esReemplazo = $item['es_reemplazo'] ?? false;
                    $originalSerial = $item['impresora_original_serial'] ?? null;

                    if ($esReemplazo && $originalSerial) {
                        return "<span class='text-info'>{$serial} (Reemplazo de: {$originalSerial})</span>";
                    }

                    return "<span class='text-success'>{$serial}</span>";
                }),

            TD::make('numero_contrato', 'Número Contrato')
                ->render(function ($item) {
                    return $item['numero_contrato'] ?? '<span class="text-warning">No disponible</span>';
                }),

            TD::make('contador_inicial', 'Último Contador')
                ->render(function ($item) {
                    return isset($item['contador_inicial']) && is_numeric($item['contador_inicial'])
                        ? number_format((float)$item['contador_inicial'])
                        : '<span class="text-danger">N/A</span>';
                }),

            TD::make('contador_final', 'Contador Actual')
                ->render(function ($item) {
                    return isset($item['contador_final']) && is_numeric($item['contador_final'])
                        ? number_format((float)$item['contador_final'])
                        : '<span class="text-danger">N/A</span>';
                }),

            TD::make('diferencia', 'Diferencia')
                ->render(function ($item) {
                    return isset($item['diferencia']) && is_numeric($item['diferencia'])
                        ? number_format((float)$item['diferencia'])
                        : '<span class="text-danger">0</span>';
                }),

            TD::make('valor_por_copia', 'Valor Copia')
                ->render(function ($item) {
                    return isset($item['valor_por_copia']) && is_numeric($item['valor_por_copia'])
                        ? '$' . number_format((float)$item['valor_por_copia'], 2)
                        : '<span class="text-warning">N/A</span>';
                }),
                TD::make('copias_minimas', 'Copias Mínimas')
                ->render(function ($item) {
                    // Verifica si es directo o si copias_minimas no está definido
                    if ($item['tipo_minimo'] === 'directo' || empty($item['copias_minimas'])) {
                        return '<span class="text-warning">N/A</span>'; // Mostramos 'N/A' en amarillo
                    }
            
                    // En caso contrario, renderiza el valor como número formateado
                    return number_format((float)$item['copias_minimas'], 0, '', '.');
                }),
            
            

                // TD::make('copias_minimas', 'Minimo Individual')
                // ->render(function ($item) {
                //     return isset($item['copias_minimas']) && is_numeric($item['copias_minimas'])
                //         ? number_format((float)$item['copias_minimas'])
                //         : '<span class="text-warning">N/A</span>';
                // }),



            TD::make('total_costo', 'Costo Total')
                ->render(function ($item) {
                    if (isset($item['diferencia']) && isset($item['valor_por_copia']) &&
                        is_numeric($item['diferencia']) && is_numeric($item['valor_por_copia'])) {
                        $costoTotal = (float)$item['diferencia'] * (float)$item['valor_por_copia'];
                        return '$' . number_format($costoTotal, 2);
                    }

                    return '<span class="text-warning">N/A</span>';
                }),
        ];
    }
}
