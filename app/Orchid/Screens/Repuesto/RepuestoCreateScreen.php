<?php

namespace App\Orchid\Screens\Repuesto;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Toast;
use App\Models\Repuesto; // Importar el modelo Repuesto
use Illuminate\Http\Request;

class RepuestoCreateScreen extends Screen
{
    /**
     * Permiso requerido para acceder a esta pantalla.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.repuestos.create']; // Permiso requerido para crear repuestos
    }

    /**
     * Nombre que se muestra en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Crear Repuesto'; // Cambiar a "Crear Repuesto"
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
                ->method('save'), // Método para guardar
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
                Input::make('repuesto.nombre') // Campo para el nombre del repuesto
                    ->title('Nombre')
                    ->placeholder('Ingrese el nombre del repuesto')
                    ->required(),

                Input::make('repuesto.contador_vida_util') // Campo para el contador de vida útil
                    ->title('Contador de Vida Útil')
                    ->placeholder('Ingrese el contador de vida útil')
                    ->required(), // Cambiar según sea necesario
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
     * Guardar el nuevo repuesto en la base de datos.
     *
     * @param Request $request
     */
    public function save(Request $request)
    {
        // Crear el nuevo repuesto
        Repuesto::create($request->get('repuesto')); // Crear el repuesto con los datos del formulario

        // Mostrar mensaje de éxito
        Toast::info('Repuesto creado correctamente.'); // Mensaje de éxito
    }
}
