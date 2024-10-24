<?php

namespace App\Orchid\Layouts;

use App\Models\Impresora;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Alert;


class ImpresoraContadorEditableLayout extends Table
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
                    return $impresora->contrato ? $impresora->contrato->numero_contrato : 'Sin contrato';
                }),

            TD::make('contador_actual', 'Contador Actual')
                ->render(function (Impresora $impresora) {
                    // Campo Input para modificar el contador actual
                    return Input::make('impresoras[' . $impresora->id . '].contador_actual')
                        ->type('number')
                        ->value($impresora->contador_actual)
                        ->placeholder('Introduce el nuevo valor del contador');
                }),


            TD::make('ultimo_contador', 'Último Contador')
                ->render(function (Impresora $impresora) {
                    $ultimoHistorial = $impresora->ultimoHistorial();
                    return $ultimoHistorial ? number_format($ultimoHistorial->contador) : 'N/A';
                }),

            TD::make('diferencia', 'Diferencia')
                ->render(function (Impresora $impresora) {
                    $ultimoHistorial = $impresora->ultimoHistorial();
                    if ($ultimoHistorial) {
                        $diferencia = $impresora->contador_actual - $ultimoHistorial->contador;
                        return number_format($diferencia);
                    }
                    return 'N/A';
                }),


                TD::make('boton_actualizar', 'Acciones')
                ->render(function (Impresora $impresora) {
                    // Botón para confirmar la actualización de cada impresora
                    return Button::make('Confirmar')
                        ->icon('check')
                        ->method('actualizarContador', [
                            'impresora_id' => $impresora->id,
                        ])
                        ->class('btn btn-success');
                }),

           
        ];
    }
}