<?php

namespace App\Orchid\Layouts;

use App\Models\Marca;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Alert;

class MarcaListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'marcas';

    /**
     * Método para eliminar una marca.
     */
    public function remove($id)
    {
        // Verifica permisos para eliminar
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
     * Definición de columnas.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('nombre', 'Nombre')
                ->render(function (Marca $marca) {
                    return Link::make($marca->nombre)
                        ->route('platform.marcas.edit', $marca->id);
                }),

            TD::make('descripcion', 'Descripción'),

            TD::make('created_at', 'Creado'),

            TD::make('updated_at', 'Última edición'),

            TD::make('Acciones')
            ->align(TD::ALIGN_CENTER)
            ->render(function (Marca $marca) {
                return auth()->user()->hasAccess('platform.marcas.edit') 
                    ? Link::make('Editar')
                        ->route('platform.marcas.edit', $marca->id)
                        ->icon('pencil')
                    : null;
            }),

        TD::make('Acciones')
            ->align(TD::ALIGN_CENTER)
            ->render(function (Marca $marca) {
                return auth()->user()->hasAccess('platform.marcas.delete') 
                    ? Button::make('Eliminar')
                        ->icon('trash')
                        ->confirm(__('¿Estás seguro de que deseas eliminar este cliente?'))
                        ->method('remove', ['id' => $marca->id])
                    : null;
            }),
    ];
}
}