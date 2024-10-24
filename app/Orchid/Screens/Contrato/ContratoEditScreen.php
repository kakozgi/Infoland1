<?php

namespace App\Orchid\Screens\Contrato;

use App\Models\Contrato;
use App\Models\Cliente;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Datetimer;
use Orchid\Screen\Fields\Number;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;

class ContratoEditScreen extends Screen
{
    public $contrato;

    /**
     * Consulta los datos del contrato.
     *
     * @return array
     */
    public function query(Contrato $contrato): array
    {
        return [
            'contrato' => $contrato,
        ];
    }

    /**
     * Permiso requerido para ver este Screen.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.contratos.edit']; // Permiso requerido para editar contratos
    }

    /**
     * Nombre mostrado en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Editar Contrato';
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
                Select::make('contrato.cliente_id')
                    ->title('Cliente')
                    ->options(Cliente::all()->pluck('nombre', 'id')->toArray())
                    ->placeholder('Seleccione un cliente')
                    ->required()
                    ->value($this->contrato->cliente_id),

                Input::make('contrato.numero_contrato')
                    ->title('Número de Contrato')
                    ->placeholder('Ingrese el número del contrato')
                    ->required()
                    ->value($this->contrato->numero_contrato),

                Datetimer::make('contrato.fecha_inicio')
                    ->title('Fecha de Inicio')
                    ->required()
                    ->value($this->contrato->fecha_inicio),

                Datetimer::make('contrato.fecha_fin')
                    ->title('Fecha de Fin')
                    ->required()
                    ->value($this->contrato->fecha_fin),

                Input::make('contrato.copias_minimas')
                    ->title('Copias Mínimas')
                  
                    ->value($this->contrato->copias_minimas),

                Input::make('contrato.valor_por_copia')
                    ->title('Valor por Copia')
                    ->placeholder('Ingrese el valor por copia')
                    ->required()
                    ->value($this->contrato->valor_por_copia),

                Select::make('contrato.tipo_minimo')
                    ->title('Tipo de Mínimo')
                    ->options([
                        'individual' => 'Individual',
                        'grupal' => 'Grupal',
                        'directo' => 'Directo',  // Nuevo tipo de contrato
                    ])
                    ->required()
                    ->value($this->contrato->tipo_minimo),
            ]),
        ];
    }

    /**
     * Guarda los cambios en el contrato.
     *
     * @param Contrato $contrato
     * @param Request $request
     */
    public function save(Contrato $contrato, Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'contrato.cliente_id' => 'required|exists:clientes,id',
            'contrato.numero_contrato' => 'required|string|max:255',
            'contrato.fecha_inicio' => 'required|date',
            'contrato.fecha_fin' => 'required|date',
       
            'contrato.valor_por_copia' => 'required|numeric|min:0',
            'contrato.tipo_minimo' => 'required|in:individual,grupal,directo',

            'contrato.copias_minimas' => [
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('contrato.tipo_minimo') === 'individual' && !empty($value)) {
                        $fail('No puedes ingresar copias mínimas si el tipo de mínimo es Individual.');
                    }
                },
            ],
        ]);

        // Actualizar el contrato con los datos validados
        $contrato->fill($validated['contrato'])->save();

        // Mostrar mensaje de éxito
        Toast::info('Contrato actualizado correctamente.');

        // Redirigir a la lista de contratos después de guardar
        return redirect()->route('platform.contratos.list');
    }
}
