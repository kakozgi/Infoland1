<?php

namespace App\Orchid\Layouts;

use App\Models\Cliente;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;

class ClienteListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'clientes';

    /**
     * Método para eliminar un cliente.
     */
    public function remove($id)
    {
        // Verifica permisos para eliminar
        if (!auth()->user()->hasAccess('platform.clientes.delete')) {
            Alert::error('No tienes permisos para eliminar este cliente.');
            return redirect()->route('platform.clientes.list');
        }

        // Eliminar cliente
        Cliente::findOrFail($id)->delete();

        Alert::success('Cliente eliminado exitosamente.');
        return redirect()->route('platform.clientes.list');
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
                ->render(function (Cliente $cliente) {
                    return Link::make($cliente->nombre)
                        ->route('platform.clientes.edit', $cliente->id);
                }),

            TD::make('rut', 'RUT'),

            TD::make('correo', 'Correo'),

            TD::make('telefono', 'Teléfono'),

            TD::make('direccion', 'Dirección'),

            TD::make('created_at', 'Creado'),

            TD::make('updated_at', 'Última edición'),

            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (Cliente $cliente) {
                    return auth()->user()->hasAccess('platform.clientes.edit') 
                        ? Link::make('Editar')
                            ->route('platform.clientes.edit', $cliente->id)
                            ->icon('pencil')
                        : null;
                }),

            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (Cliente $cliente) {
                    return auth()->user()->hasAccess('platform.clientes.delete') 
                        ? Button::make('Eliminar')
                            ->icon('trash')
                            ->confirm(__('¿Estás seguro de que deseas eliminar este cliente?'))
                            ->method('remove', ['id' => $cliente->id])
                        : null;
                }),
        ];
    }
}
