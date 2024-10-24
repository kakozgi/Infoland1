<?php

namespace App\Orchid\Screens\Factura;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Support\Facades\Toast;
use App\Models\Factura;
use Illuminate\Http\Request;
use App\Models\Contrato;
use Orchid\Screen\Fields\Select;

class FacturaCreateScreen extends Screen
{
    /**
     * Permiso requerido para acceder a esta pantalla.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.facturas.create']; // Permiso requerido para crear facturas
    }

    /**
     * Nombre que se muestra en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Crear Factura';
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
     * Layouts y campos para el formulario.
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
                    ->required()
                    ->options(Contrato::all()->pluck('numero_contrato', 'id')->toArray()),
                    
                DateTimer::make('factura.fecha_factura')
                    ->title('Fecha de la factura')
                    ->placeholder('Seleccione la fecha de la factura')
                    ->required(),

                Input::make('factura.valor_total')
                    ->title('Valor Total')
                    ->placeholder('Ingrese el valor total de la factura')
                    ->required()
                    ->type('number')
                    ->min(0),
            ]),
        ];
    }

    /**
     * Consulta de datos para la pantalla (no se necesita aquí, retorna vacío).
     *
     * @return array
     */
    public function query(): array
    {
        return []; // No necesitamos datos para este formulario
    }

    /**
     * Guardar la nueva factura en la base de datos.
     *
     * @param Request $request
     */
    public function save(Request $request)
    {
        // Crear la nueva factura
        Factura::create($request->get('factura'));

        // Mostrar mensaje de éxito
        Toast::info('Factura creada correctamente.');
    }
}
