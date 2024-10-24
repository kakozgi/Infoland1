<?php

namespace App\Orchid\Layouts;

use App\Models\Reemplazo;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Alert;

class ReemplazoListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'reemplazos'; // Nombre de la variable que proviene del query de la pantalla

    /**
     * Método para eliminar un reemplazo.
     */
    public function remove($id)
    {
        // Verificar permisos para eliminar
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
     * Definición de columnas.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id_impresora_original', 'Impresora Original')
                ->render(function (Reemplazo $reemplazo) {
                    return Link::make($reemplazo->impresoraOriginal->serial ?? 'N/A')
                        ->route('platform.reemplazos.edit', $reemplazo->id); // Cambiar según la ruta de edición
                }),

            TD::make('id_impresora_reemplazo', 'Impresora de Reemplazo')
                ->render(function (Reemplazo $reemplazo) {
                    return Link::make($reemplazo->impresoraReemplazo->serial ?? 'N/A')
                        ->route('platform.reemplazos.edit', $reemplazo->id); // Cambiar según la ruta de edición
                }),

            TD::make('fecha_reemplazo', 'Fecha de Reemplazo'),

            TD::make('fecha_reactivacion', 'Fecha de Reactivación'),

            TD::make('created_at', 'Creado'),

            TD::make('updated_at', 'Última edición'),

            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (Reemplazo $reemplazo) {
                    return auth()->user()->hasAccess('platform.reemplazos.edit') 
                        ? Link::make('Editar')
                            ->route('platform.reemplazos.edit', $reemplazo->id)
                            ->icon('pencil')
                        : null;
                }),

            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (Reemplazo $reemplazo) {
                    return auth()->user()->hasAccess('platform.reemplazos.delete') 
                        ? Button::make('Eliminar')
                            ->icon('trash')
                            ->confirm(__('¿Estás seguro de que deseas eliminar este reemplazo?'))
                            ->method('remove', ['id' => $reemplazo->id])
                        : null;
                }),
        ];
    }
}
