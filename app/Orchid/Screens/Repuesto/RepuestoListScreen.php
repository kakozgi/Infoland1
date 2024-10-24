<?php

namespace App\Orchid\Screens\Repuesto;

use App\Models\Repuesto;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\RepuestoListLayout; // Asegúrate de importar el layout de Repuesto
use Orchid\Support\Facades\Alert;

class RepuestoListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'repuestos' => Repuesto::paginate(), // Cambia a 'repuestos'
        ];
    }

    /**
     * Permisos necesarios para ver este Screen.
     *
     */
    public function permission(): ?iterable
    {
        return [
            'platform.repuestos.view', // Cambia a permisos de repuestos
        ];
    }

    /**
     * El nombre que se muestra en la pantalla y en los encabezados.
     *
     */
    public function name(): ?string
    {
        return 'Repuestos'; // Cambia a "Repuestos"
    }

    /**
     * La descripción que se muestra en la pantalla debajo del encabezado.
     *
     */
    public function description(): ?string
    {
        return 'Lista de todos los repuestos'; // Cambia la descripción
    }

    /**
     * Elimina un repuesto.
     *
     */
    public function remove($id)
    {
        // Verificar permiso para eliminar
        if (!auth()->user()->hasAccess('platform.repuestos.delete')) { // Cambia a permisos de repuestos
            Alert::error('No tienes permisos para eliminar este repuesto.');
            return redirect()->route('platform.repuestos.list'); // Cambia la ruta
        }

        // Eliminar repuesto
        Repuesto::findOrFail($id)->delete();

        Alert::success('Repuesto eliminado exitosamente.');
        return redirect()->route('platform.repuestos.list'); // Cambia la ruta
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return auth()->user()->hasAccess('platform.repuestos.create') // Cambia a permisos de repuestos
            ? [
                Link::make('Crear nuevo repuesto') // Cambia el texto
                    ->icon('plus')
                    ->route('platform.repuestos.create'), // Cambia la ruta
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
            RepuestoListLayout::class, // Asegúrate de usar el layout de repuesto
        ];
    }
}
