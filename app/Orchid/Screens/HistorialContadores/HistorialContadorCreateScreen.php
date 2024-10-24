<?php

namespace App\Orchid\Screens\HistorialContadores;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Support\Facades\Toast;
use App\Models\HistorialContador;
use App\Models\Impresora;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Select;

class HistorialContadorCreateScreen extends Screen
{
    /**
     * Permiso requerido para acceder a esta pantalla.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.historiales.create']; // Permiso requerido para crear historiales de contador
    }

    /**
     * Nombre que se muestra en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Crear Historial de Contador';
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
                Select::make('historial.impresora_id')
                    ->title('ID de Impresora')
                    ->placeholder('Ingrese el ID de la impresora')
                    ->fromModel(Impresora::class, 'serial', 'id')
                    ->required()
                    ->help('Este campo debe contener el identificador de la impresora.'),

                Input::make('historial.contador')
                    ->title('Contador Actual')
                    ->placeholder('Ingrese el valor del contador actual')
                    ->required()
                    ->type('number')
                    ->min(0),

                DateTimer::make('historial.fecha_registro')
                    ->title('Fecha de Registro')
                    ->placeholder('Seleccione la fecha de registro')
                    ->required()
                    ->format('Y-m-d'),
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
     * Guardar el nuevo historial en la base de datos.
     *
     * @param Request $request
     */
    public function save(Request $request)
    {
        // Validar y crear el nuevo historial de contador
        $request->validate([
            'historial.impresora_id' => 'required|exists:impresoras,id',
            'historial.contador' => 'required|integer|min:0',
            'historial.fecha_registro' => 'required|date',
        ]);

        HistorialContador::create($request->get('historial'));

        // Mostrar mensaje de éxito
        Toast::info('Historial de contador creado correctamente.');
    }
}
