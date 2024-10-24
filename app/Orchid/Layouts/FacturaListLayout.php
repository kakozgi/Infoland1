<?php

namespace App\Orchid\Layouts;

use App\Models\Factura;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Alert;

class FacturaListLayout extends Table
{
    /**
     * Fuente de datos.
     *
     * @var string
     */
    public $target = 'facturas';

    /**
     * Método para eliminar una factura.
     */
    public function remove($id)
    {
        // Verifica permisos para eliminar
        if (!auth()->user()->hasAccess('platform.facturas.delete')) {
            Alert::error('No tienes permisos para eliminar esta factura.');
            return redirect()->route('platform.facturas.list');
        }

        // Eliminar factura
        Factura::findOrFail($id)->delete();

        Alert::success('Factura eliminada exitosamente.');
        return redirect()->route('platform.facturas.list');
    }

    /**
     * Definición de columnas.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [

            TD::make('contrato_id', 'Numero de Contrato')
                ->sort()
                ->filter()
                ->render(function ($factura) {
                    return $factura->contrato ? $factura->contrato->numero_contrato : 'N/A';
                }),

            TD::make('valor_total', 'Valor Total')
                ->sort()
                ->filter()
                ->render(function ($factura) {
                    return '$' . number_format($factura->valor_total, 2);
                }),

                TD::make('fecha_factura', 'Fecha de Factura')
                ->sort()
                ->filter()
                ->render(function ($factura) {
                    return $factura->fecha_factura;
                }),


            TD::make('created_at', 'Creado'),

            TD::make('updated_at', 'Última edición'),

            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (Factura $factura) {
                    return auth()->user()->hasAccess('platform.facturas.edit') 
                        ? Link::make('Editar')
                            ->route('platform.facturas.edit', $factura->id)
                            ->icon('pencil')
                        : null;
                }),

            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (Factura $factura) {
                    return auth()->user()->hasAccess('platform.facturas.delete') 
                        ? Button::make('Eliminar')
                            ->icon('trash')
                            ->confirm(__('¿Estás seguro de que deseas eliminar esta factura?'))
                            ->method('remove', ['id' => $factura->id])
                        : null;
                }),
        ];
    }
}
