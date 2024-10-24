<?php

namespace App\Orchid\Screens\Marca;

use App\Models\Marca;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\MarcaListLayout;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Layouts\Table;


class MarcaListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'marcas' => Marca::paginate(),
        ];
    }

    /**
     * Permisos necesarios para ver este Screen.
     *
     */
    public function permission(): ?iterable
    {
        return [
            'platform.marcas.view',
        ];
    }

    /**
     * El nombre que se muestra en la pantalla y en los encabezados.
     *
     */
    public function name(): ?string
    {
        return 'Marcas';
    }

    /**
     * La descripción que se muestra en la pantalla debajo del encabezado.
     *
     */
    public function description(): ?string
    {
        return 'Lista de todas las marcas';
    }

    /**
     * Elimina una marca.
     *
     */
    public function remove($id)
    {
        // Verificar permiso para eliminar
        if (!auth()->user()->hasAccess('platform.marcas.delete')) {
            Alert::error('No tienes permisos para eliminar esta marca.');
            return redirect()->route('platform.marcas.list');
        }

        // Eliminar marca
        Marca::findOrFail($id)->delete();

        Alert::success('Marca eliminada exitosamente.');
        return redirect()->route('platform.marcas.list');
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return auth()->user()->hasAccess('platform.marcas.create')
            ? [
                Link::make('Crear nueva marca')
                    ->icon('plus')
                    ->route('platform.marcas.create'),
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
            MarcaListLayout::class,
        ];
    }
}
