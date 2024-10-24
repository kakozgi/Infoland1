<?php

namespace App\Orchid\Screens;

use App\Models\Contrato;
use App\Models\Impresora;
use App\Models\ContratoImpresora;
use Orchid\Screen\Screen;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Toast;

class ContratoImpresorasScreen extends Screen
{
    public function name(): string
    {
        return 'Seleccionar Contrato y Visualizar Impresoras';
    }

    /**
     * Query para cargar los datos iniciales.
     */
    public function query(): array
    {
        // Obtener el contrato seleccionado si existe
        $contratoSeleccionado = request('contrato_id');
        $impresoras = [];

        // Si hay un contrato seleccionado, cargar las impresoras asociadas
        if ($contratoSeleccionado) {
            $impresoras = Impresora::where('contrato_id', $contratoSeleccionado)->get();
        }

        return [
            'impresoras' => $impresoras,  // Lista de impresoras asociadas al contrato
            'contratoSeleccionado' => $contratoSeleccionado,  // Mantener la selección en el select
        ];
    }

    /**
     * Layout de la pantalla.
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                // Dropdown para seleccionar contrato
                Select::make('contrato_id')
                    ->fromModel(Contrato::class, 'numero_contrato', 'id')
                    ->title('Selecciona un Contrato')
                    ->empty('Selecciona un Contrato')
                    ->help('Este filtro muestra las impresoras asociadas al contrato seleccionado')
                    ->value(request('contrato_id') ?? $this->query()['contratoSeleccionado'])

                    // Concatenar el nombre del cliente con el número de contrato
                    ->options(
                        Contrato::where('tipo_minimo', 'individual')
                            ->with('cliente')  // Asegurarse de cargar el cliente relacionado
                            ->get()
                            ->mapWithKeys(function ($contrato) {
                                // Concatenar el nombre del cliente con el número de contrato
                                return [$contrato->id => $contrato->cliente->nombre . ' - ' . $contrato->numero_contrato];
                            })
                    ),
                // Botón para confirmar la selección
                Button::make('Confirmar Selección')
                    ->icon('check')
                    ->method('actualizarPagina')
                    ->style('background-color: #28a745; color: white;'),
            ]),

            // Tabla para mostrar las impresoras asociadas al contrato
            Layout::table('impresoras', [
                TD::make('serial', 'Serial Impresora')
                    ->render(function (Impresora $impresora) {
                        return $impresora->serial;
                    }),

                TD::make('modelo', 'Modelo Impresora')
                    ->render(function (Impresora $impresora) {
                        return $impresora->modeloImpresora->nombre ?? 'Sin modelo';
                    }),

                // Campo para ingresar las copias mínimas
                TD::make('copias_minimas', 'Copias Mínimas')
                    ->render(function (Impresora $impresora) {
                        $contratoImpresora = ContratoImpresora::where('contrato_id', request('contrato_id'))
                            ->where('impresora_id', $impresora->id)
                            ->first();

                        return Input::make('copias_minimas[' . $impresora->id . ']')
                            ->type('number')
                            ->value($contratoImpresora ? $contratoImpresora->copias_minimas : 0)  // Cargar el valor si ya existe
                            ->min(0)
                            ->placeholder('Ingresa copias mínimas');
                    }),

                // Botón de guardar para cada impresora
                TD::make('actions', 'Acciones')
                    ->render(function (Impresora $impresora) {
                        return Button::make('Guardar')
                            ->icon('save')
                            ->method('save', [
                                'impresora_id' => $impresora->id,
                                'contrato_id' => request('contrato_id')
                            ]);
                    }),
            ]),
        ];
    }

    /**
     * Redirigir para cargar las impresoras asociadas al contrato seleccionado.
     */
    public function actualizarPagina()
    {
        $contratoSeleccionado = request('contrato_id');

        // Redirigir a la misma ruta con el contrato seleccionado para recargar las impresoras
        return redirect()->route('platform.contratos_impresoras', [
            'contrato_id' => $contratoSeleccionado
        ])->withInput();
    }

    /**
     * Guardar las copias mínimas en la tabla contratos_impresoras.
     */
    public function save(Request $request)
    {
        // Validar los datos, asegurándose de capturar las copias mínimas
        $validatedData = $request->validate([
            'contrato_id' => 'required|exists:contratos,id',
            'impresora_id' => 'required|exists:impresoras,id',
            'copias_minimas' => 'required|array',
            'copias_minimas.*' => 'required|integer|min:0',
        ]);

        // Verificar si ya existe el registro en la tabla contratos_impresoras
        $contratoImpresora = ContratoImpresora::updateOrCreate(
            [
                'contrato_id' => $request->contrato_id,
                'impresora_id' => $request->impresora_id,
            ],
            [
                'copias_minimas' => $validatedData['copias_minimas'][$request->impresora_id],
            ]
        );

        Toast::info('Copias mínimas guardadas correctamente.');
    }
}
