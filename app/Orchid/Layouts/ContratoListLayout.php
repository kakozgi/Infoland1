<?php

namespace App\Orchid\Layouts;

use App\Models\Contrato;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Alert;

class ContratoListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'contratos';

    /**
     * Método para eliminar un contrato.
     */
    public function remove($id)
    {
        // Verifica permisos para eliminar
        if (!auth()->user()->hasAccess('platform.contratos.delete')) {
            Alert::error('No tienes permisos para eliminar este contrato.');
            return redirect()->route('platform.contratos.list');
        }

        // Eliminar contrato
        Contrato::findOrFail($id)->delete();

        Alert::success('Contrato eliminado exitosamente.');
        return redirect()->route('platform.contratos.list');
    }

    /**
     * Definición de columnas.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('numero_contrato', 'Número de contrato')
                ->render(function (Contrato $contrato) {
                    return Link::make($contrato->numero_contrato)
                        ->route('platform.contratos.edit', $contrato->id);
                }),

            TD::make('cliente_id', 'Cliente')
                ->render(function (Contrato $contrato) {
                    return $contrato->cliente ? $contrato->cliente->nombre : 'Sin Cliente';
                }),

            TD::make('fecha_inicio', 'Fecha de inicio'),
            TD::make('fecha_fin', 'Fecha de fin'),
            TD::make('copias_minimas', 'Copias mínimas'),
            TD::make('valor_por_copia', 'Valor por copia'),
            TD::make('tipo_minimo', 'Tipo mínimo'),

            TD::make('created_at', 'Creado'),
            TD::make('updated_at', 'Última edición'),

            // Acción de edición
            TD::make('Acciones')
            ->align(TD::ALIGN_CENTER)
            ->render(function (Contrato $contrato) {
                return auth()->user()->hasAccess('platform.contratos.edit') 
                    ? Link::make('Editar')
                        ->route('platform.contratos.edit', $contrato->id)
                        ->icon('pencil')
                    : null;
            }),

            // Acción de eliminación
            TD::make('Acciones')
            ->align(TD::ALIGN_CENTER)
            ->render(function (Contrato $contrato) {
                return auth()->user()->hasAccess('platform.contratos.delete') 
                    ? Button::make('Eliminar')
                        ->icon('trash')
                        ->confirm(__('¿Estás seguro de que deseas eliminar este contrato?'))
                        ->method('remove', ['id' => $contrato->id])
                    : null;
            }),
        ];
    }
}
