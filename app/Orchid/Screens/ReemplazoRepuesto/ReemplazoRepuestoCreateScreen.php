<?php

namespace App\Orchid\Screens\ReemplazoRepuesto;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Facades\Toast;
use App\Models\ReemplazoRepuesto;
use App\Models\Impresora;
use App\Models\Repuesto;
use Illuminate\Http\Request;

class ReemplazoRepuestoCreateScreen extends Screen
{
    /**
     * Permiso requerido para acceder a esta pantalla.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.reemplazosRepuestos.create']; // Permiso requerido para crear reemplazos de repuestos
    }

    /**
     * Nombre que se muestra en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Crear Reemplazo de Repuesto';
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
                Relation::make('reemplazoRepuesto.id_impresora')
                    ->title('Impresora')
                    ->fromModel(Impresora::class, 'nombre')
                    ->required()
                    ->placeholder('Seleccione una impresora'),

                Relation::make('reemplazoRepuesto.id_repuesto')
                    ->title('Repuesto')
                    ->fromModel(Repuesto::class, 'nombre')
                    ->required()
                    ->placeholder('Seleccione un repuesto'),

                Input::make('reemplazoRepuesto.contador_inicial')
                    ->title('Contador Inicial')
                    ->type('number')
                    ->required()
                    ->placeholder('Ingrese el valor del contador inicial'),

                Input::make('reemplazoRepuesto.fecha_instalacion')
                    ->title('Fecha de Instalación')
                    ->type('date')
                    ->required()
                    ->placeholder('Seleccione la fecha de instalación'),
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
        return []; // No se necesitan datos previos para este formulario
    }

    /**
     * Guardar el nuevo reemplazo de repuesto en la base de datos.
     *
     * @param Request $request
     */
    public function save(Request $request)
    {
        // Validar y crear el nuevo reemplazo de repuesto
        $validated = $request->validate([
            'reemplazoRepuesto.id_impresora' => 'required|exists:impresoras,id',
            'reemplazoRepuesto.id_repuesto' => 'required|exists:repuestos,id',
            'reemplazoRepuesto.contador_inicial' => 'required|integer',
            'reemplazoRepuesto.fecha_instalacion' => 'required|date',
        ]);

        // Crear el reemplazo de repuesto con los datos validados
        ReemplazoRepuesto::create($validated['reemplazoRepuesto']);

        // Mostrar mensaje de éxito
        Toast::info('Reemplazo de repuesto creado correctamente.');
    }
}
