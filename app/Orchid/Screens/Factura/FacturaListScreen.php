<?php

namespace App\Orchid\Screens\Factura;

use App\Models\Factura;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\FacturaListLayout;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Layouts\Table;

class FacturaListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'facturas' => Factura::paginate(),
        ];
    }

    /**
     * Permisos necesarios para ver este Screen.
     *
     */
    public function permission(): ?iterable
    {
        return [
            'platform.facturas.view',
        ];
    }

    /**
     * El nombre que se muestra en la pantalla y en los encabezados.
     *
     */
    public function name(): ?string
    {
        return 'Facturas';
    }

    /**
     * La descripción que se muestra en la pantalla debajo del encabezado.
     *
     */
    public function description(): ?string
    {
        return 'Lista de todas las facturas';
    }

    /**
     * Elimina una factura.
     *
     */
    public function remove($id)
    {
        // Verificar permiso para eliminar
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
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return auth()->user()->hasAccess('platform.facturas.create')
            ? [
                Link::make('Crear nueva factura')
                    ->icon('plus')
                    ->route('platform.facturas.create'),
              ]
            : [];
    }

    /**
     * Views.
     *
     * @return array
     */
    public function layout(): array
    {
        return [
            FacturaListLayout::class,
        ];
    }
}
