<?php

namespace App\Orchid\Screens\Marca;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Toast;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaCreateScreen extends Screen
{
    /**
     * Permiso requerido para acceder a esta pantalla.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.marcas.create']; // Permiso requerido para crear marcas
    }

    /**
     * Nombre que se muestra en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Crear Marca';
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
                Input::make('marca.nombre')
                    ->title('Nombre')
                    ->placeholder('Ingrese el nombre de la marca')
                    ->required(),

                Input::make('marca.descripcion')
                    ->title('Descripción')
                    ->placeholder('Ingrese una breve descripción'),
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
     * Guardar la nueva marca en la base de datos.
     *
     * @param Request $request
     */
    public function save(Request $request)
    {
        // Crear la nueva marca
        Marca::create($request->get('marca'));

        // Mostrar mensaje de éxito
        Toast::info('Marca creada correctamente.');
    }
}
