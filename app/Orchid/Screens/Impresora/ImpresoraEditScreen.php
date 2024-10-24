<?php

namespace App\Orchid\Screens\Impresora;

use App\Models\Impresora;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Toast;
use App\Models\ModeloImpresora;
use App\Models\Contrato;

class ImpresoraEditScreen extends Screen
{
    public $impresora;

    /**
     * Query data.
     *
     * @param Impresora $impresora
     * @return array
     */
    public function query(Impresora $impresora): array
    {
        return [
            'impresora' => $impresora,
        ];
    }

    /**
     * Permiso requerido para ver este Screen.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.impresoras.edit']; // Permiso requerido para editar impresoras
    }

    /**
     * Nombre mostrado en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Editar Impresora';
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
                Input::make('impresora.serial')
                    ->title('Serial')
                    ->placeholder('Ingrese el serial de la impresora')
                    ->required(),

                Select::make('impresora.id_modelo')
                    ->title('Modelo de Impresora')
                    ->fromModel(ModeloImpresora::class, 'nombre', 'id')
                    ->required()
                    ->empty('Seleccione un modelo'),

                Select::make('impresora.contrato_id')
                    ->title('Contrato')
                    ->fromModel(Contrato::class, 'numero_contrato', 'id')
                    ->empty('Seleccione un contrato'),
                
                    Select::make('impresora.estado')
                    ->title('Estado')
                    ->options([
                        'contrato' => 'Contrato',
                        'disponible' => 'Disponible',
                        'servicio_tecnico' => 'Servicio Técnico',
                        'desarme' => 'Desarme',
                        'recambio' => 'Recambio',
                    ])
                    ->default('disponible')
                    ->required(),

                Input::make('impresora.ubicacion')
                    ->title('Ubicación')
                    ->placeholder('Ingrese la ubicación de la impresora'),

                Input::make('impresora.telefono')
                    ->title('Teléfono de Contacto')
                    ->placeholder('Ingrese el número de teléfono'),

                Input::make('impresora.contador_actual')
                    ->title('Contador Actual')
                    ->placeholder('Ingrese el contador actual')
                    ->type('number'),
            ]),
        ];
    }

    /**
     * Guarda los cambios en la impresora.
     *
     * @param Impresora $impresora
     * @param Request $request
     */
    public function save(Impresora $impresora, Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'impresora.serial' => 'required|string|max:255',
            'impresora.id_modelo' => 'required|exists:modelo_impresoras,id',
            'impresora.contrato_id' => 'nullable|exists:contratos,id',
            'impresora.estado' => 'required|in:contrato,disponible,servicio_tecnico,desarme,recambio',
            'impresora.ubicacion' => 'nullable|string|max:255',
            'impresora.telefono' => 'nullable|string|max:255',
            'impresora.contador_actual' => 'nullable|numeric',
        ]);

        // Actualizar la impresora con los datos validados
        $impresora->fill($validated['impresora'])->save();

        // Mostrar un mensaje de éxito
        Toast::info('Impresora actualizada correctamente.');
    }
}
