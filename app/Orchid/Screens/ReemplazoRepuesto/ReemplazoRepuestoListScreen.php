<?php

namespace App\Orchid\Screens\ReemplazoRepuesto;

use App\Models\ReemplazoRepuesto;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\ReemplazoRepuestoListLayout;
use Orchid\Support\Facades\Alert;

class ReemplazoRepuestoListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'reemplazosRepuestos' => ReemplazoRepuesto::paginate(),
        ];
    }

    /**
     * Permisos necesarios para ver este Screen.
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.reemplazosRepuestos.view',
        ];
    }

    /**
     * El nombre que se muestra en la pantalla y en los encabezados.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Reemplazos de Repuestos';
    }

    /**
     * La descripción que se muestra en la pantalla debajo del encabezado.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Lista de todos los reemplazos de repuestos registrados';
    }

    /**
     * Elimina un reemplazo de repuesto.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($id)
    {
        // Verificar permiso para eliminar
        if (!auth()->user()->hasAccess('platform.reemplazosRepuestos.delete')) {
            Alert::error('No tienes permisos para eliminar este reemplazo de repuesto.');
            return redirect()->route('platform.reemplazosRepuestos.list');
        }

        // Eliminar el reemplazo de repuesto
        ReemplazoRepuesto::findOrFail($id)->delete();

        Alert::success('Reemplazo de repuesto eliminado exitosamente.');
        return redirect()->route('platform.reemplazosRepuestos.list');
    }

    /**
     * Botones de comandos.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return auth()->user()->hasAccess('platform.reemplazosRepuestos.create')
            ? [
                Link::make('Crear nuevo reemplazo de repuesto')
                    ->icon('plus')
                    ->route('platform.reemplazosRepuestos.create'),
              ]
            : [];
    }

    /**
     * Vistas.
     *
     * @return array
     */
    public function layout(): array
    {
        return [
            ReemplazoRepuestoListLayout::class,
        ];
    }
}
