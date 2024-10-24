<?php

namespace App\Orchid\Layouts;

use App\Models\ReemplazoRepuesto;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Alert;

class ReemplazoRepuestoListLayout extends Table
{
    /**
     * Fuente de datos.
     *
     * @var string
     */
    public $target = 'reemplazosRepuestos';

    /**
     * Método para eliminar un reemplazo de repuesto.
     */
    public function remove($id)
    {
        // Verifica permisos para eliminar
        if (!auth()->user()->hasAccess('platform.reemplazosRepuestos.delete')) {
            Alert::error('No tienes permisos para eliminar este reemplazo de repuesto.');
            return redirect()->route('platform.reemplazosRepuestos.list');
        }

        // Eliminar reemplazo de repuesto
        ReemplazoRepuesto::findOrFail($id)->delete();

        Alert::success('Reemplazo de repuesto eliminado exitosamente.');
        return redirect()->route('platform.reemplazosRepuestos.list');
    }

    /**
     * Definición de columnas.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('impresora.nombre', 'Impresora') // Relación con la impresora
                ->render(function (ReemplazoRepuesto $reemplazo) {
                    return Link::make($reemplazo->impresora->nombre)
                        ->route('platform.reemplazosRepuestos.edit', $reemplazo->id);
                }),

            TD::make('repuesto.nombre', 'Repuesto') // Relación con el repuesto
                ->render(function (ReemplazoRepuesto $reemplazo) {
                    return $reemplazo->repuesto->nombre;
                }),

            TD::make('contador_inicial', 'Contador Inicial'),

            TD::make('fecha_instalacion', 'Fecha de Instalación')
                ->render(function (ReemplazoRepuesto $reemplazo) {
                    return $reemplazo->fecha_instalacion->toDateString();
                }),

            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (ReemplazoRepuesto $reemplazo) {
                    return auth()->user()->hasAccess('platform.reemplazosRepuestos.edit') 
                        ? Link::make('Editar')
                            ->route('platform.reemplazosRepuestos.edit', $reemplazo->id)
                            ->icon('pencil')
                        : null;
                }),

            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (ReemplazoRepuesto $reemplazo) {
                    return auth()->user()->hasAccess('platform.reemplazosRepuestos.delete') 
                        ? Button::make('Eliminar')
                            ->icon('trash')
                            ->confirm(__('¿Estás seguro de que deseas eliminar este reemplazo de repuesto?'))
                            ->method('remove', ['id' => $reemplazo->id])
                        : null;
                }),
        ];
    }
}
