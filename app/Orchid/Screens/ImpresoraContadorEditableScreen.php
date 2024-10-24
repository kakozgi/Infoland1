<?php

namespace App\Orchid\Screens;

use App\Models\Impresora;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use App\Orchid\Layouts\ImpresoraContadorEditableLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Select;
use App\Models\Cliente;


class ImpresoraContadorEditableScreen extends Screen
{
    public function name(): string
    {
        return 'Modificar Contadores de Impresoras por Cliente';
    }

    public function query(): array
    {
        $clienteSeleccionado = request()->get('cliente_id');
        $impresoras = collect();

        if ($clienteSeleccionado) {
            // Obtener impresoras asociadas al cliente seleccionado
            $impresoras = Impresora::join('contratos', 'impresoras.contrato_id', '=', 'contratos.id')
                ->where('contratos.cliente_id', $clienteSeleccionado)
                ->select('impresoras.*')
                ->get();
        }

        return [
            'impresoras' => $impresoras,
            'clienteSeleccionado' => $clienteSeleccionado,
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                // Selección de cliente
                Select::make('cliente_id')
                    ->fromModel(Cliente::class, 'nombre')
                    ->empty('Selecciona un Cliente', 0)
                    ->title('Selecciona un Cliente')
                    ->help('Este filtro muestra las impresoras del cliente seleccionado')
                    ->value(request('cliente_id') ?? $this->query()['clienteSeleccionado']),

                Button::make('Confirmar Selección')
                    ->icon('check')
                    ->method('actualizarPagina')
                    ->style('background-color: #28a745; color: white;'),
            ]),

            // Tabla editable de impresoras
            ImpresoraContadorEditableLayout::class,
        ];
    }

    public function actualizarPagina(Request $request)
    {
        $clienteSeleccionado = $request->get('cliente_id');

        return redirect()->route('platform.impresoras_contador_editable', [
            'cliente_id' => $clienteSeleccionado,
        ]);
    }

    public function actualizarContador(Request $request)
    {
        $impresoraId = $request->get('impresora_id');
        $nuevoContador = $request->input("impresoras.$impresoraId.contador_actual");

        // Actualizar la impresora
        $impresora = Impresora::find($impresoraId);
        if ($impresora && $nuevoContador) {
            $impresora->contador_actual = $nuevoContador;
            $impresora->save();
        }

        return redirect()->back()->with('success', 'Contador actualizado correctamente.');
    }
}
