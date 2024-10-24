<?php

namespace App\Orchid\Screens\ReemplazoRepuesto;

use App\Models\ReemplazoRepuesto;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Toast;

class ReemplazoRepuestoEditScreen extends Screen
{
    public $reemplazoRepuesto;

    /**
     * Query data.
     *
     * @param ReemplazoRepuesto $reemplazoRepuesto
     * @return array
     */
    public function query(ReemplazoRepuesto $reemplazoRepuesto): array
    {
        return [
            'reemplazoRepuesto' => $reemplazoRepuesto,
        ];
    }

    /**
     * Permiso requerido para ver este Screen.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.reemplazosRepuestos.edit']; // Permiso requerido para editar reemplazos de repuestos
    }

    /**
     * Nombre mostrado en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Editar Reemplazo de Repuesto';
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
                Select::make('reemplazoRepuesto.id_impresora')
                    ->title('Impresora')
                    ->fromModel(\App\Models\Impresora::class, 'nombre', 'id')
                    ->required(),

                Select::make('reemplazoRepuesto.id_repuesto')
                    ->title('Repuesto')
                    ->fromModel(\App\Models\Repuesto::class, 'nombre', 'id')
                    ->required(),

                Input::make('reemplazoRepuesto.contador_inicial')
                    ->title('Contador Inicial')
                    ->type('number')
                    ->required(),

                Input::make('reemplazoRepuesto.fecha_instalacion')
                    ->title('Fecha de Instalación')
                    ->type('date')
                    ->required(),
            ]),
        ];
    }

    /**
     * Guarda los cambios en el reemplazo de repuesto.
     *
     * @param ReemplazoRepuesto $reemplazoRepuesto
     * @param Request $request
     */
    public function save(ReemplazoRepuesto $reemplazoRepuesto, Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'reemplazoRepuesto.id_impresora' => 'required|exists:impresoras,id',
            'reemplazoRepuesto.id_repuesto' => 'required|exists:repuestos,id',
            'reemplazoRepuesto.contador_inicial' => 'required|integer',
            'reemplazoRepuesto.fecha_instalacion' => 'required|date',
        ]);

        // Actualizar el reemplazo de repuesto con los datos validados
        $reemplazoRepuesto->fill($validated['reemplazoRepuesto'])->save();

        // Mostrar un mensaje de éxito
        Toast::info('Reemplazo de repuesto actualizado correctamente.');
    }
}
