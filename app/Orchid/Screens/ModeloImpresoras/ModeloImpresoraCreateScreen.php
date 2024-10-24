<?php

namespace App\Orchid\Screens\ModeloImpresoras;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Toast;
use App\Models\ModeloImpresora; // Asegúrate de importar el modelo correcto
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Select;
use App\Models\Marca;

class ModeloImpresoraCreateScreen extends Screen
{
    /**
     * Permiso requerido para acceder a esta pantalla.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.modelos_impresora.create']; // Permiso requerido para crear modelos de impresora
    }

    /**
     * Nombre que se muestra en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Crear Modelo de Impresora';
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
                Input::make('modelo_impresoras.nombre')
                    ->title('Nombre')
                    ->placeholder('Ingrese el nombre del modelo de impresora')
                    ->required(),

                Select::make('modelo_impresoras.id_marca')
                    ->title('Marca')
                    ->placeholder('Seleccione una marca')
                    ->help('Seleccione la marca a la que pertenece este modelo')
                    ->options(Marca::all()->pluck('nombre', 'id')),

                Input::make('modelo_impresoras.descripcion')
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
     * Guardar el nuevo modelo de impresora en la base de datos.
     *
     * @param Request $request
     */
    public function save(Request $request)
    {
        ModeloImpresora::create($request->get('modelo_impresoras'));

        // Mostrar mensaje de éxito
        Toast::info('Modelo de impresora creado correctamente.');
    }
}
