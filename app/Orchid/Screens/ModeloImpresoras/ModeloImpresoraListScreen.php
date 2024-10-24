<?php

namespace App\Orchid\Screens\ModeloImpresoras;

use App\Models\ModeloImpresora; // Asegúrate de que este modelo esté correctamente definido
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\ModeloImpresoraListLayout; // Asegúrate de crear este layout
use Orchid\Support\Facades\Alert;

class ModeloImpresoraListScreen extends Screen // Nombre de la clase
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'modelo_impresoras' => ModeloImpresora::paginate(), // Nombre de la variable actualizado
        ];
    }

    /**
     * Permisos necesarios para ver este Screen.
     *
     */
    public function permission(): ?iterable
    {
        return [
            'platform.modelos_impresora.view', // Permiso necesario
        ];
    }

    /**
     * El nombre que se muestra en la pantalla y en los encabezados.
     *
     */
    public function name(): ?string
    {
        return 'Modelos de Impresora'; // Nombre mostrado
    }

    /**
     * La descripción que se muestra en la pantalla debajo del encabezado.
     *
     */
    public function description(): ?string
    {
        return 'Lista de todos los modelos de impresora'; // Descripción mostrada
    }

    /**
     * Elimina un modelo de impresora.
     *
     */
    public function remove($id)
    {
        // Verificar permiso para eliminar
        if (!auth()->user()->hasAccess('platform.modelos_impresora.delete')) { // Permiso para eliminar
            Alert::error('No tienes permisos para eliminar este modelo de impresora.');
            return redirect()->route('platform.modelos_impresora.list'); // Ruta de redirección
        }

        // Eliminar modelo de impresora
        ModeloImpresora::findOrFail($id)->delete();

        Alert::success('Modelo de impresora eliminado exitosamente.');
        return redirect()->route('platform.modelos_impresora.list'); // Ruta de redirección
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return auth()->user()->hasAccess('platform.modelos_impresora.create') // Permiso para crear
            ? [
                Link::make('Crear nuevo modelo de impresora') // Texto del enlace
                    ->icon('plus')
                    ->route('platform.modelos_impresora.create'), // Ruta de creación
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
            ModeloImpresoraListLayout::class, // Asegúrate de crear este layout
        ];
    }
}
