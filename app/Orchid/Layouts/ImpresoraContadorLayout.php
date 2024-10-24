<?php

namespace App\Orchid\Layouts;

use App\Models\Impresora;
use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;


class ImpresoraContadorLayout extends Table
{
    public $target = 'impresoras';  // Usamos 'impresoras' que viene del Screen

    public function columns(): array
    {
        return [
            TD::make('serial', 'Serial Impresora')
                ->render(function (Impresora $impresora) {
                    return $impresora->serial;
                }),

            TD::make('numero_contrato', 'Número Contrato')
                ->render(function (Impresora $impresora) {
                    // Si la impresora tiene un contrato, mostramos el número de contrato
                    return $impresora->contrato ? $impresora->contrato->numero_contrato : 'Sin contrato';
                })
             ,
              

            TD::make('contador_actual', 'Contador Actual')
                ->render(function (Impresora $impresora) {
                    return number_format($impresora->contador_actual);
                }),

            TD::make('ultimo_contador', 'Último Contador')
                ->render(function (Impresora $impresora) {
                    $ultimoHistorial = $impresora->ultimoHistorial();
                    return $ultimoHistorial ? number_format($ultimoHistorial->contador) : 'N/A';
                }),

            TD::make('diferencia', 'Diferencia')
                ->render(function (Impresora $impresora) {
                    $ultimoHistorial = $impresora->ultimoHistorial();
                    
                    // Si existe el último historial, calculamos la diferencia
                    if ($ultimoHistorial) {
                        $diferencia = $impresora->contador_actual - $ultimoHistorial->contador;
                        return number_format($diferencia);
                    }

                    // Si no hay un historial previo, mostramos 'N/A'
                    return 'N/A';
                }),

            // Agregar valor por copia
            TD::make('valor_por_copia', 'Valor Copia')
                ->render(function (Impresora $impresora) {
                    // Accedemos al valor por copia desde el contrato asociado a la impresora
                    return $impresora->contrato
                        ? number_format($impresora->contrato->valor_por_copia)
                        : 'N/A';
                }),

            // Agregar mínimo de copias
            TD::make('copias_minimas', 'Minimo Grupal')
                ->render(function (Impresora $impresora) {
                    // Accedemos al mínimo de copias desde el contrato asociado a la impresora
                    return $impresora->contrato
                        ? $impresora->contrato->copias_minimas
                        : 'N/A';
                }),

                TD::make('copias_minimas', 'Minimo Indiviudal')
                ->render(function (Impresora $impresora) {
                    // Obtener las copias mínimas desde la tabla contratos_impresoras
                    $contratoImpresora = $impresora->contratoImpresora()->first();
                    
                    // Verificar si existe un registro en contratos_impresoras
                    return $contratoImpresora
                        ? number_format($contratoImpresora->copias_minimas)
                        : 'N/A'; // Si no existe, devolvemos 'N/A'
                }),
            

            

            // Nueva columna: diferencia multiplicada por valor por copia
            TD::make('total_costo', 'Costo Total')
                ->render(function (Impresora $impresora) {
                    $ultimoHistorial = $impresora->ultimoHistorial();
                    $diferencia = 0;

                    if ($ultimoHistorial) {
                        // Calculamos la diferencia
                        $diferencia = $impresora->contador_actual - $ultimoHistorial->contador;
                    }

                    // Calculamos el costo total: diferencia * valor por copia
                    if ($impresora->contrato && $diferencia > 0) {
                        $costoTotal = $diferencia * $impresora->contrato->valor_por_copia;
                        return '$' . number_format($costoTotal);
                    }

                    // Si no hay contrato o la diferencia es 0, devolvemos 'N/A'
                    return 'N/A';
                }),
        ];
    }
}
