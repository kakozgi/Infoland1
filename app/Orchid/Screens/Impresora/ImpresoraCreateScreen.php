<?php

namespace App\Orchid\Screens\Impresora;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Toast;
use App\Models\Impresora;
use App\Models\ModeloImpresora;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ImpresoraCreateScreen extends Screen
{
    /**
     * Permiso requerido para acceder a esta pantalla.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.impresoras.create']; // Permiso requerido para crear impresoras
    }

    /**
     * Nombre que se muestra en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Crear Impresora';
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

                Input::make('impresora.ubicacion')
                    ->title('Ubicación')
                    ->placeholder('Ingrese la ubicación de la impresora'),

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
     * Consulta de datos para la pantalla (no se necesita aquí, retorna vacío).
     *
     * @return array
     */
    public function query(): array
    {
        return []; // No se necesitan datos para este formulario
    }

    /**
     * Guardar la nueva impresora en la base de datos.
     *
     * @param Request $request
     */
    public function save(Request $request)
    {
        // Validar los datos
            $request->validate([
                'impresora.serial' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('impresoras', 'serial'), // Verifica que el serial sea único
                ],
            'impresora.id_modelo' => 'required|exists:modelo_impresoras,id', // Corregido: tabla modelo_impresoras
            'impresora.contrato_id' => 'nullable|exists:contratos,id', // Asegura que el contrato exista si es proporcionado
            'impresora.ubicacion' => 'nullable|string|max:255',
            'impresora.telefono' => 'nullable|string|max:20',
            'impresora.contador_actual' => 'nullable|numeric',
        ]);

        // Crear la nueva impresora
        Impresora::create($request->get('impresora'));

        // Mostrar mensaje de éxito
        Toast::info('Impresora creada correctamente.');
    }
}
