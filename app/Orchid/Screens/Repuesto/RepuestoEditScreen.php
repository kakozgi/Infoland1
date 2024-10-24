<?php

namespace App\Orchid\Screens\Repuesto;

use App\Models\Repuesto; // Importar el modelo Repuesto
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Toast;

class RepuestoEditScreen extends Screen
{
    public $repuesto; // Variable para almacenar el repuesto

    /**
     * Consulta de datos.
     *
     * @return array
     */
    public function query(Repuesto $repuesto): array
    {
        return [
            'repuesto' => $repuesto, // Retornar el repuesto que se está editando
        ];
    }

    /**
     * Permiso requerido para ver esta pantalla.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.repuestos.edit']; // Permiso requerido para editar repuestos
    }

    /**
     * Nombre mostrado en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Editar Repuesto'; // Cambiar a "Editar Repuesto"
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
                ->method('save'), // Método para guardar cambios
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
                Input::make('repuesto.nombre') // Campo para el nombre del repuesto
                    ->title('Nombre')
                    ->placeholder('Ingrese el nombre del repuesto')
                    ->required(), // Campo requerido

                Input::make('repuesto.contador_vida_util') // Campo para el contador de vida útil
                    ->title('Contador de Vida Útil')
                    ->placeholder('Ingrese el contador de vida útil')
                    ->required(), // Campo requerido
            ]),
        ];
    }

    /**
     * Guarda los cambios en el repuesto.
     *
     * @param Repuesto $repuesto
     * @param Request $request
     */
    public function save(Repuesto $repuesto, Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'repuesto.nombre' => 'required|string|max:255',
            'repuesto.contador_vida_util' => 'required|integer', // Validar que sea un entero
        ]);

        // Actualizar el repuesto con los datos validados
        $repuesto->fill($validated['repuesto'])->save();

        // Mostrar un mensaje de éxito
        Toast::info('Repuesto actualizado correctamente.'); // Mensaje de éxito
    }
}
