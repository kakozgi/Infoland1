<?php

namespace App\Orchid\Screens\Impresora;

use App\Models\Impresora;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\ImpresoraListLayout;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;

class ImpresoraListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $search = request()->get('search', '');
        $estado = request()->get('estado', ''); // Obtenemos el filtro de estado

        $impresoras = Impresora::query()
            // Filtro por búsqueda de serial
            ->when($search, function ($query, $search) {
                return $query->where('serial', 'like', '%' . $search . '%');
            })
            // Filtro por estado
            ->when($estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })
            ->paginate(100);

        return [
            'impresoras' => $impresoras,
        ];
    }

    /**
     * Permisos necesarios para ver este Screen.
     *
     */
    public function permission(): ?iterable
    {
        return [
            'platform.impresoras.view',
        ];
    }

    /**
     * El nombre que se muestra en la pantalla y en los encabezados.
     *
     */
    public function name(): ?string
    {
        return 'Impresoras';
    }

    /**
     * La descripción que se muestra en la pantalla debajo del encabezado.
     *
     */
    public function description(): ?string
    {
        return 'Lista de todas las impresoras';
    }

    /**
     * Elimina una impresora.
     *
     */
    public function remove($id)
    {
        // Verificar permiso para eliminar
        if (!auth()->user()->hasAccess('platform.impresoras.delete')) {
            Alert::error('No tienes permisos para eliminar esta impresora.');
            return redirect()->route('platform.impresoras.list');
        }

        // Eliminar impresora
        Impresora::findOrFail($id)->delete();

        Alert::success('Impresora eliminada exitosamente.');
        return redirect()->route('platform.impresoras.list');
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return auth()->user()->hasAccess('platform.impresoras.create')
            ? [
                Link::make('Crear nueva impresora')
                    ->icon('plus')
                    ->route('platform.impresoras.create'),
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

            
            ImpresoraListLayout::class,
        ];
    }
}
