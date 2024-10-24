<?php

namespace App\Orchid\Layouts;

use App\Models\HistorialContador;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Alert;

class HistorialContadorListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'historiales'; // Nombre de la variable que proviene del query de la pantalla

    /**
     * Método para eliminar un historial de contador.
     */
    public function remove($id)
    {
        // Verificar permisos para eliminar
        if (!auth()->user()->hasAccess('platform.historiales.delete')) {
            Alert::error('No tienes permisos para eliminar este historial.');
            return redirect()->route('platform.historiales.list');
        }

        // Eliminar historial de contador
        HistorialContador::findOrFail($id)->delete();

        Alert::success('Historial de contador eliminado exitosamente.');
        return redirect()->route('platform.historiales.list');
    }

    /**
     * Definición de columnas.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('serial', 'Serial de Impresora')
                ->render(function (HistorialContador $historial) {
                    return Link::make($historial->impresora->serial);
                }),

            TD::make('contador', 'Contador Actual')
                ->sort()
                ->render(function (HistorialContador $historial) {
                    return number_format($historial->contador);
                }),

            TD::make('fecha_registro', 'Fecha de Registro')
                ->sort()
                ->render(function (HistorialContador $historial) {
                    return $historial->fecha_registro;
                }),

            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (HistorialContador $historial) {
                    return auth()->user()->hasAccess('platform.historiales.edit') 
                        ? Link::make('Editar')
                            ->route('platform.historiales.edit', $historial->id)
                            ->icon('pencil')
                        : null;
                }),

            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (HistorialContador $historial) {
                    return auth()->user()->hasAccess('platform.historiales.delete') 
                        ? Button::make('Eliminar')
                            ->icon('trash')
                            ->confirm(__('¿Estás seguro de que deseas eliminar este historial?'))
                            ->method('remove', ['id' => $historial->id])
                        : null;
                }),
        ];
    }
}
