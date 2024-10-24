<?php

namespace App\Orchid\Layouts;

use App\Models\ContratoImpresora;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Alert;

class ContratoImpresoraListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'contratosImpresoras';

    /**
     * Método para eliminar un contrato de impresora.
     */
    public function remove($id)
    {
        // Verificar permisos para eliminar
        if (!auth()->user()->hasAccess('platform.contratosImpresoras.delete')) {
            Alert::error('No tienes permisos para eliminar este registro.');
            return redirect()->route('platform.contratosImpresoras.list');
        }

        // Eliminar contrato de impresora
        ContratoImpresora::findOrFail($id)->delete();

        Alert::success('Contrato de impresora eliminado exitosamente.');
        return redirect()->route('platform.contratosImpresoras.list');
    }

    

    /**
     * Definición de columnas.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('contrato_id', 'Contrato')
                ->render(function (ContratoImpresora $contratoImpresora) {
                    return Link::make($contratoImpresora->contrato->numero_contrato)
                        ->route('platform.contratos.edit', $contratoImpresora->contrato_id);
                }),

            TD::make('impresora_id', 'Impresora')
                ->render(function (ContratoImpresora $contratoImpresora) {
                    return Link::make($contratoImpresora->impresora->serial)
                        ->route('platform.impresoras.edit', $contratoImpresora->impresora_id);
                }),

            TD::make('copias_minimas', 'Copias Mínimas')
                ->render(function (ContratoImpresora $contratoImpresora) {
                    return $contratoImpresora->copias_minimas;
                }),

            TD::make('created_at', 'Creado'),

            TD::make('updated_at', 'Última edición'),

            TD::make('Acciones')
            ->align(TD::ALIGN_CENTER)
            ->render(function (ContratoImpresora $contratoImpresora) {
                return auth()->user()->hasAccess('platform.contratosImpresoras.edit') 
                    ? Link::make('Editar')
                        ->route('platform.contratosImpresoras.edit', $contratoImpresora->id)
                        ->icon('pencil')
                    : null;
            }),

            TD::make('Acciones')
            ->align(TD::ALIGN_CENTER)
            ->render(function (ContratoImpresora $contratoImpresora) {
                return auth()->user()->hasAccess('platform.contratosImpresoras.delete') 
                    ? Button::make('Eliminar')
                        ->icon('trash')
                        ->confirm(__('¿Estás seguro de que deseas eliminar este registro?'))
                        ->method('remove', ['id' => $contratoImpresora->id])
                    : null;
            }),
        ];
    }
}
