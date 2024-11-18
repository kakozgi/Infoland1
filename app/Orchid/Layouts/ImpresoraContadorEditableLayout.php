<?php

namespace App\Orchid\Layouts;

use App\Models\Impresora;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;

class ImpresoraContadorEditableLayout extends Table
{
    public $target = 'impresoras';

    public function columns(): array
    {
        return [
            TD::make('serial', 'Serial Impresora')
            ->render(function (Impresora $impresora) {
                // Calcular si fue modificada recientemente (últimos 7 días)
                $esReciente = $impresora->updated_at && $impresora->updated_at->greaterThanOrEqualTo(now()->subDays(7));

                // Aplicar una clase para destacar si fue actualizada recientemente
                $class = $esReciente ? 'text-success font-weight-bold' : '';

                return "<span class='$class'>{$impresora->serial}</span>";
            }),

            TD::make('numero_contrato', 'Número Contrato')
                ->render(function (Impresora $impresora) {
                    return $impresora->contrato ? $impresora->contrato->numero_contrato : 'Sin contrato';
                }),

            TD::make('contador_actual', 'Contador Actual')
                ->render(function (Impresora $impresora) {
                    return Input::make("contador_actual_{$impresora->id}")
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
                    return Button::make('Confirmar')
                        ->icon('check')
                        ->method('actualizarContador', [
                            'impresora_id' => $impresora->id,
                            'contador_actual' => "contador_actual_{$impresora->id}",
                        ])
                        ->class('btn btn-success');
                }),
        ];
    }
}
