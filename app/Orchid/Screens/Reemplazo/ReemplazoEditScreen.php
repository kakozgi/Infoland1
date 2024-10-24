<?php

namespace App\Orchid\Screens\Reemplazo;

use App\Models\Reemplazo; // Importando el modelo de Reemplazo
use App\Models\Impresora; // Importar el modelo Impresora
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\DateTimer;

class ReemplazoEditScreen extends Screen
{
    public $reemplazo;

    /**
     * Query data.
     *
     * @param Reemplazo $reemplazo
     * @return array
     */
    public function query(Reemplazo $reemplazo): array
    {
        // Asegúrate de devolver el modelo correctamente para que sus valores puedan prellenar los campos
        return [
            'reemplazo' => $reemplazo, // Esto se enlazará con el formulario de edición          
        ];
    }

    /**
     * Permiso requerido para acceder a esta pantalla.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.reemplazos.edit']; // Permiso requerido para editar reemplazos
    }

    /**
     * Nombre que se muestra en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Editar Reemplazo';
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
                Select::make('reemplazo.id_impresora_original') // Campo para impresora original
                    ->title('Impresora Original')
                    ->placeholder('Seleccione la impresora original')
                    ->fromQuery(Impresora::contratadas(), 'serial', 'id') // Filtrar impresoras en contrato
                    ->value($this->reemplazo->id_impresora_original) // Prellenar con el valor actual
                    ->required(),

                Select::make('reemplazo.id_impresora_reemplazo') // Campo para impresora de reemplazo
                    ->title('Impresora de Reemplazo')
                    ->placeholder('Seleccione la impresora de reemplazo')
                    ->fromQuery(Impresora::disponibles(), 'serial', 'id') // Filtrar impresoras disponibles
                    ->value($this->reemplazo->id_impresora_reemplazo) // Prellenar con el valor actual
                    ->required(),

                DateTimer::make('reemplazo.fecha_reemplazo') // Campo para la fecha de reemplazo
                    ->title('Fecha de Reemplazo')
                    ->placeholder('Ingrese la fecha del reemplazo')
                    ->value($this->reemplazo->fecha_reemplazo) // Prellenar con el valor actual
                    ->required()
                 ,

                DateTimer::make('reemplazo.fecha_reactivacion') // Campo para la fecha de reactivación
                    ->title('Fecha de Reactivación')
                    ->placeholder('Ingrese la fecha de reactivación')
                    ->value($this->reemplazo->fecha_reactivacion) // Prellenar con el valor actual
                ,
            ]),
        ];
    }

    /**
     * Guardar los cambios en el reemplazo.
     *
     * @param Reemplazo $reemplazo
     * @param Request $request
     */
    public function save(Reemplazo $reemplazo, Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'reemplazo.id_impresora_original' => 'required|exists:impresoras,id',
            'reemplazo.id_impresora_reemplazo' => 'required|exists:impresoras,id',
            'reemplazo.fecha_reemplazo' => 'required|date',
            'reemplazo.fecha_reactivacion' => 'nullable|date',
        ]);

        // Actualizar el reemplazo con los datos validados
        $reemplazo->update($validated['reemplazo']);

        // Mostrar un mensaje de éxito
        Toast::info('Reemplazo actualizado correctamente.');
    }
}
