<?php

namespace App\Orchid\Screens\ContratoImpresoras;

use App\Models\Contrato;
use App\Models\ContratoImpresora;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\ContratoImpresoraListLayout;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Illuminate\Http\Request;

class ContratoImpresoraListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $contratoId = request()->get('contrato_id');

        return [
            'contratos' => Contrato::pluck('numero_contrato', 'id'),
            'contratosImpresoras' => $contratoId 
                ? ContratoImpresora::where('contrato_id', $contratoId)->paginate() 
                : ContratoImpresora::paginate(),
            'selectedContratoId' => $contratoId,
        ];
    }

    public function permission(): ?iterable
    {
        return [
            'platform.contratosImpresoras.view',
        ];
    }

    public function remove($id)
    {
        if (!auth()->user()->hasAccess('platform.contratosImpresoras.delete')) {
            Alert::error('No tienes permisos para eliminar este contrato de impresora.');
            return redirect()->route('platform.contratosImpresoras.list');
        }

        ContratoImpresora::findOrFail($id)->delete();

        Alert::success('Contrato de impresora eliminado exitosamente.');
        return redirect()->route('platform.contratosImpresoras.list');
    }

    /**
     * Actualiza la página con el contrato seleccionado.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmar(Request $request)
    {
        $contratoSeleccionado = $request->get('contrato_id');

        return redirect()->route('platform.contratosImpresoras.list', [
            'contrato_id' => $contratoSeleccionado,
        ]);
    }

    public function commandBar(): array
    {
        return auth()->user()->hasAccess('platform.contratosImpresoras.create')
            ? [
                Link::make('Crear nuevo contrato de impresora')
                    ->icon('plus')
                    ->route('platform.contratosImpresoras.create'),
              ]
            : [];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Select::make('contrato_id')
                    ->fromQuery(Contrato::query(), 'numero_contrato', 'id')
                    ->empty('Selecciona un contrato')
                    ->title('Seleccionar Contrato')
                    ->help('Selecciona un contrato para ver las impresoras asociadas.')
                    ->value(request()->get('contrato_id') ?? $this->query()['selectedContratoId'])
                    ->required(),
            
                Button::make('Confirmar Selección')
                    ->icon('check')
                    ->help('Filtrar por contrato')
                    ->method('confirmar') 
                    ->style('background-color: #28a745; color: white;'),
            ]),
            
            ContratoImpresoraListLayout::class,
        ];
    }
}
