<?php

namespace App\Orchid\Screens\Cliente;

use App\Models\Cliente;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\ClienteListLayout;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Alert;

class ClienteListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'clientes' => Cliente::paginate(),
        ];
    }

    public function permission(): ?iterable
    {
        return [
            'platform.clientes.view', 
        
        ];
    }

    /**
     * The name is displayed on the user's screen and in the headers.
     */
    public function name(): ?string
    {
        return 'Clientes';
    }

    /**
     * The description is displayed on the user's screen under the heading.
     */
    public function description(): ?string
    {
        return 'Lista de todos los clientes';
    }

    public function remove($id)
    {
        // Verificar permiso para eliminar
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
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return auth()->user()->hasAccess('platform.clientes.create') 
            ? [
                Link::make('Crear nuevo cliente')
                    ->icon('plus')
                    ->route('platform.clientes.create'),
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
            ClienteListLayout::class,
        ];
    }
}
