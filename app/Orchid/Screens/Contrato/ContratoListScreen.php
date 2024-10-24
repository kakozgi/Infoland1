<?php

namespace App\Orchid\Screens\Contrato;

use App\Models\Contrato;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\ContratoListLayout;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Layouts\Table;

class ContratoListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'contratos' => Contrato::paginate(),  // Pagina los contratos
        ];
    }

    /**
     * Permisos necesarios para ver este Screen.
     *
     */
    public function permission(): ?iterable
    {
        return [
            'platform.contratos.view',  // Verificar el permiso para acceder a la lista de contratos
        ];
    }

    /**
     * El nombre que se muestra en la pantalla y en los encabezados.
     *
     */
    public function name(): ?string
    {
        return 'Contratos';  // Título que se muestra en la pantalla
    }

    /**
     * La descripción que se muestra en la pantalla debajo del encabezado.
     *
     */
    public function description(): ?string
    {
        return 'Lista de todos los contratos';  // Descripción bajo el título
    }

    /**
     * Elimina un contrato.
     *
     */
    public function remove($id)
    {
        // Verificar si el usuario tiene permisos para eliminar
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
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return auth()->user()->hasAccess('platform.contratos.create')
            ? [
                Link::make('Crear nuevo contrato')
                    ->icon('plus')
                    ->route('platform.contratos.create'),  // Enlace para crear un nuevo contrato
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
            ContratoListLayout::class,  // Usar el layout que muestra la lista de contratos
        ];
    }
}
