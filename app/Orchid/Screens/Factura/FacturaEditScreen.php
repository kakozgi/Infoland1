<?php

namespace App\Orchid\Screens\Factura;

use App\Models\Factura;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Select;
use App\Models\Contrato;
use Orchid\Screen\Fields\DateTimer;

class FacturaEditScreen extends Screen
{
    public $factura;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Factura $factura): array
    {
        return [
            'factura' => $factura,
        ];
    }

    /**
     * Permiso requerido para ver este Screen.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.facturas.edit']; // Permiso requerido para editar facturas
    }

    /**
     * Nombre mostrado en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Editar Factura';
    }

    /**
     * Botones de la barra de comandos.
     *
     * @return Button[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Guardar')
                ->method('save'),
        ];
    }

    /**
     * Layouts para el formulario de edición.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                    Select::make('factura.contrato_id')
                        ->title('Numero de contrato')
                        ->placeholder('Ingrese el numero del contrato')
                        ->require()
                        ->options(Contrato::all()->pluck('numero_contrato', 'id')->toArray()),
                        
                    DateTimer::make('factura.fecha_factura')
                        ->title('Fecha de la factura')
                        ->require()
                        ->placeholder('Seleccione la fecha de la factura')
                        ,
                    Input::make('factura.valor_total')
                        ->title('Valor Total')
                        ->placeholder('Ingrese el valor total de la factura')
                        ->require()
                        ->type('number')
                        ->min(0),
            ]),
        ];
    }

    /**
     * Guarda los cambios en la factura.
     *
     * @param Factura $factura
     * @param Request $request
     */
    public function save(Factura $factura, Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'factura.contrato_id' => 'required|exists:contratos,id',
            'factura.fecha_factura' => 'required|date',
            'factura.valor_total' => 'required|numeric|min:0',
        ]);

        // Actualizar la factura con los datos validados
        $factura->fill($validated['factura'])->save();

        // Mostrar un mensaje de éxito
        Toast::info('Factura actualizada correctamente.');

        return redirect()->route('platform.facturas.list'); // Redirigir a la lista de facturas
    }
}
