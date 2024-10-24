<?php

namespace App\Orchid\Screens\Reemplazo;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Toast;
use App\Models\Reemplazo;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Select;
use App\Models\Impresora;
use Orchid\Screen\Fields\Datetimer;

class ReemplazoCreateScreen extends Screen
{
    /**
     * Permiso requerido para acceder a esta pantalla.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.reemplazos.create']; // Permiso requerido para crear reemplazos
    }

    /**
     * Nombre que se muestra en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Crear Reemplazo';
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
                Select::make('reemplazo.id_impresora_original') // Campo para impresora original
                    ->title('Impresora Original')
                    ->placeholder('Seleccione la impresora original')
                    ->fromQuery(Impresora::contratadas(), 'serial', 'id') // Filtrar impresoras en contrato
                    ->required(),

                Select::make('reemplazo.id_impresora_reemplazo') // Campo para impresora de reemplazo
                    ->title('Impresora de Reemplazo')
                    ->placeholder('Seleccione la impresora de reemplazo')
                    ->fromQuery(Impresora::disponibles(), 'serial', 'id') // Filtrar impresoras disponibles
                    ->required(),

                Datetimer::make('reemplazo.fecha_reemplazo') // Campo para fecha de reemplazo
                    ->title('Fecha de Reemplazo')
                    ->placeholder('Ingrese la fecha del reemplazo')
                    ->required()
                    ->type('date'),

                Datetimer::make('reemplazo.fecha_reactivacion') // Campo para fecha de reactivación
                    ->title('Fecha de Reactivación')
                    ->placeholder('Ingrese la fecha de reactivación')
                    ->type('date'),
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
     * Guardar el nuevo reemplazo en la base de datos.
     *
     * @param Request $request
     */
    public function save(Request $request)
    {
        // Obtener los IDs de las impresoras del request
        $idImpresoraOriginal = $request->input('reemplazo.id_impresora_original');
        $idImpresoraReemplazo = $request->input('reemplazo.id_impresora_reemplazo');
    
        // Obtener las impresoras de la base de datos
        $impresoraOriginal = Impresora::findOrFail($idImpresoraOriginal);
        $impresoraReemplazo = Impresora::findOrFail($idImpresoraReemplazo);
    
        // Guardar el contrato de la impresora original antes de cambiarlo
        $contratoIdOriginal = $impresoraOriginal->contrato_id;
    
        // Disociar la impresora original del contrato (dejar su contrato en null)
        $impresoraOriginal->update([
            'contrato_id' => null,
            'estado' => 'recambio', // Cambiar el estado de la impresora original a "reemplazo"
        ]);
    
        // Asociar la impresora de reemplazo al contrato de la impresora original
        $impresoraReemplazo->update([
            'contrato_id' => $contratoIdOriginal,
            'estado' => 'contrato', // Cambiar el estado de la impresora de reemplazo a "contrato"
        ]);
    
        // Crear el nuevo reemplazo en la tabla 'reemplazos'
        Reemplazo::create([
            'id_impresora_original' => $idImpresoraOriginal,
            'id_impresora_reemplazo' => $idImpresoraReemplazo,
            'fecha_reemplazo' => $request->input('reemplazo.fecha_reemplazo'),
            'fecha_reactivacion' => $request->input('reemplazo.fecha_reactivacion'),
        ]);
    
        // Mostrar mensaje de éxito
        Toast::info('Reemplazo creado correctamente.');
    }
}
