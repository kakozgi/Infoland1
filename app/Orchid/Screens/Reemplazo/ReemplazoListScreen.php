<?php

namespace App\Orchid\Screens\Reemplazo;

use App\Models\Reemplazo;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\ReemplazoListLayout; // Cambiado a tu nuevo layout
use Orchid\Support\Facades\Alert;

class ReemplazoListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'reemplazos' => Reemplazo::paginate(), // Cambiado para cargar reemplazos
        ];
    }

    /**
     * Permisos necesarios para ver este Screen.
     *
     */
    public function permission(): ?iterable
    {
        return [
            'platform.reemplazos.view', // Permisos necesarios para ver reemplazos
        ];
    }

    /**
     * El nombre que se muestra en la pantalla y en los encabezados.
     *
     */
    public function name(): ?string
    {
        return 'Reemplazos';
    }

    /**
     * La descripción que se muestra en la pantalla debajo del encabezado.
     *
     */
    public function description(): ?string
    {
        return 'Lista de todos los reemplazos';
    }

    /**
     * Elimina un reemplazo.
     *
     */
    public function remove($id)
    {
        // Verificar permiso para eliminar
        if (!auth()->user()->hasAccess('platform.reemplazos.delete')) {
            Alert::error('No tienes permisos para eliminar este reemplazo.');
            return redirect()->route('platform.reemplazos.list');
        }

        // Eliminar reemplazo
        Reemplazo::findOrFail($id)->delete();

        Alert::success('Reemplazo eliminado exitosamente.');
        return redirect()->route('platform.reemplazos.list');
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return auth()->user()->hasAccess('platform.reemplazos.create')
            ? [
                Link::make('Crear nuevo reemplazo')
                    ->icon('plus')
                    ->route('platform.reemplazos.create'), // Cambiado a la ruta de crear reemplazo
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
            ReemplazoListLayout::class, // Cambiado al nuevo layout
        ];
    }
}
