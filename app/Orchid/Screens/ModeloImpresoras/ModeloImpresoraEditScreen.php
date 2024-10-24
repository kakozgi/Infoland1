<?php

namespace App\Orchid\Screens\ModeloImpresoras;

use App\Models\ModeloImpresora;
use App\Models\Marca;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Toast;

class ModeloImpresoraEditScreen extends Screen
{
    public $modeloImpresora;

    /**
     * Query data.
     *
     * @param ModeloImpresora $modeloImpresora
     * @return array
     */
    public function query(ModeloImpresora $modeloImpresora): array
    {
        // Asegúrate de devolver el modelo correctamente para que sus valores puedan prellenar los campos
        return [
            'modeloImpresora' => $modeloImpresora, // Esto se enlazará con el formulario
        ];
    }

    /**
     * Permiso requerido para acceder a esta pantalla.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.modelos_impresora.edit']; // Permiso requerido para editar modelos de impresora
    }

    /**
     * Nombre que se muestra en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Editar Modelo de Impresora';
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
                Input::make('modeloImpresora.nombre') // Asegúrate de que el campo esté enlazado al nombre del modelo
                    ->title('Nombre')
                    ->placeholder('Ingrese el nombre del modelo de impresora')
                    ->value($this->modeloImpresora->nombre) // Prellenar con el valor actual
                    ->required(),

                Select::make('modeloImpresora.id_marca') // Relacionar con el campo id_marca
                    ->title('Marca')
                    ->options(Marca::all()->pluck('nombre', 'id')) // Mostrar las marcas disponibles
                    ->value($this->modeloImpresora->id_marca) // Prellenar con el valor actual
                    ->required(),

                Input::make('modeloImpresora.descripcion') // Relacionar con la descripción del modelo
                    ->title('Descripción')
                    ->placeholder('Ingrese una breve descripción')
                    ->value($this->modeloImpresora->descripcion), // Prellenar con el valor actual
            ]),
        ];
    }

    /**
     * Guardar los cambios en el modelo de impresora.
     *
     * @param ModeloImpresora $modeloImpresora
     * @param Request $request
     */
    public function save(ModeloImpresora $modeloImpresora, Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'modeloImpresora.nombre' => 'required|string|max:255',
            'modeloImpresora.id_marca' => 'required|exists:marcas,id',
            'modeloImpresora.descripcion' => 'nullable|string|max:500',
        ]);

        // Actualizar el modelo de impresora con los datos validados
        $modeloImpresora->update($validated['modeloImpresora']);

        // Mostrar un mensaje de éxito
        Toast::info('Modelo de impresora actualizado correctamente.');
    }
}
