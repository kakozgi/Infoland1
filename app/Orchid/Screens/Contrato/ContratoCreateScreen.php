<?php

namespace App\Orchid\Screens\Contrato;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Toast;
use App\Models\Contrato;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\DateTimer;

class ContratoCreateScreen extends Screen
{
    /**
     * Permiso requerido para acceder a esta pantalla.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.contratos.create']; // Permiso requerido para crear contratos
    }

    /**
     * Nombre que se muestra en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Crear Contrato';
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
    public function query(): array
{
    return [];
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
                Select::make('contrato.cliente_id')
                    ->title('Cliente')
                    ->options(Cliente::all()->pluck('nombre', 'id')->toArray())
                    ->placeholder('Seleccione un cliente')
                    ->required(),

                Input::make('contrato.numero_contrato')
                    ->title('Número de Contrato')
                    ->placeholder('Ingrese el número del contrato')
                    ->required(),

                DateTimer::make('contrato.fecha_inicio')
                    ->title('Fecha de Inicio')
                    ->required(),

                DateTimer::make('contrato.fecha_fin')
                    ->title('Fecha de Fin')
                    ->required(),

                    Select::make('contrato.tipo_minimo')
                    ->title('Tipo de Mínimo')
                    ->options([
                        'individual' => 'Individual',
                        'grupal' => 'Grupal',
                        'directo' => 'Directo',  // Nuevo tipo de contrato
                    ])
                    ->required(),
                

                Input::make('contrato.copias_minimas')
                    ->title('Copias Mínimas')
                    ->help('Solo ingresa un valor si el tipo de mínimo es "Grupal".'),
                    
                Input::make('contrato.valor_por_copia')
                    ->title('Valor por Copia')
                    ->placeholder('Ingrese el valor por copia')
                    ->required(),
            ]),
        ];
    }

    /**
     * Reglas de validación para el contrato.
     *
     * @param Request $request
     * @return array
     */
    public function rules(Request $request): array
{
    return [
        'contrato.cliente_id' => ['required', 'exists:clientes,id'], // Validamos que cliente_id es requerido y debe existir en la tabla `clientes`
        'contrato.numero_contrato' => ['required', 'string', 'max:255'],
        'contrato.fecha_inicio' => ['required', 'date'],
        'contrato.fecha_fin' => ['required', 'date'],
        'contrato.valor_por_copia' => ['required', 'numeric'],
        'contrato.tipo_minimo' => ['required', 'in:individual,grupal,directo'],

        // Validación personalizada para copias_minimas
        'contrato.copias_minimas' => [
            function ($attribute, $value, $fail) use ($request) {
                if ($request->input('contrato.tipo_minimo') === 'individual' && !empty($value)) {
                    $fail('No puedes ingresar copias mínimas si el tipo de mínimo es Individual.');
                }
            },
        ],
    ];
}


    /**
     * Guardar el nuevo contrato en la base de datos.
     *
     * @param Request $request
     */
    public function save(Request $request)
    {
        // Aplicar validación
        $validatedData = $request->validate($this->rules($request));

        // Crear el nuevo contrato
        Contrato::create($validatedData['contrato']);

        // Mostrar mensaje de éxito
        Toast::info('Contrato creado correctamente.');

        // Redirigir a la lista de contratos después de guardar
        return redirect()->route('platform.contratos.list');
    }
}
