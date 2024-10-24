<?php

namespace App\Orchid\Screens\Marca;

use App\Models\Marca;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Toast;

class MarcaEditScreen extends Screen
{
    public $marca;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Marca $marca): array
    {
        return [
            'marca' => $marca,
        ];
    }

    /**
     * Permiso requerido para ver este Screen.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        

        return ['platform.marcas.edit']; // Permiso requerido para editar marcas
    }

    /**
     * Nombre mostrado en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Editar Marca';
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
                Input::make('marca.nombre')
                    ->title('Nombre')
                    ->required(),

                  
                Input::make('marca.descripcion')
                    ->title('Descripción')
                  
            ]),
        ];
    }

    /**
     * Guarda los cambios en la marca.
     *
     * @param Marca $marca
     * @param Request $request
     */
    public function save(Marca $marca, Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'marca.nombre' => 'required|string|max:255',
            'marca.descripcion' => 'nullable|string|max:255',
        ]);

        // Actualizar la marca con los datos validados
        $marca->fill($validated['marca'])->save();

        // Mostrar un mensaje de éxito
        Toast::info('Marca actualizada correctamente.');
    }
}
