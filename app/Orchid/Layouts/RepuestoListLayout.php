<?php

namespace App\Orchid\Layouts;

use App\Models\Repuesto;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Alert;

class RepuestoListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'repuestos';

    /**
     * Método para eliminar un repuesto.
     */
    public function remove($id)
    {
        // Verifica permisos para eliminar
        if (!auth()->user()->hasAccess('platform.repuestos.delete')) {
            Alert::error('No tienes permisos para eliminar este repuesto.');
            return redirect()->route('platform.repuestos.list');
        }

        // Eliminar repuesto
        Repuesto::findOrFail($id)->delete();

        Alert::success('Repuesto eliminado exitosamente.');
        return redirect()->route('platform.repuestos.list');
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
                ->render(function (Repuesto $repuesto) {
                    return Link::make($repuesto->nombre)
                        ->route('platform.repuestos.edit', $repuesto->id);
                }),

            TD::make('contador_vida_util', 'Contador Vida Útil'),

            TD::make('created_at', 'Creado'),

            TD::make('updated_at', 'Última edición'),

            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (Repuesto $repuesto) {
                    return auth()->user()->hasAccess('platform.repuestos.edit') 
                        ? Link::make('Editar')
                            ->route('platform.repuestos.edit', $repuesto->id)
                            ->icon('pencil')
                        : null;
                }),

            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (Repuesto $repuesto) {
                    return auth()->user()->hasAccess('platform.repuestos.delete') 
                        ? Button::make('Eliminar')
                            ->icon('trash')
                            ->confirm(__('¿Estás seguro de que deseas eliminar este repuesto?'))
                            ->method('remove', ['id' => $repuesto->id])
                        : null;
                }),
        ];
    }
}
