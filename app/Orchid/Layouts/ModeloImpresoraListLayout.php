<?php

namespace App\Orchid\Layouts;

use App\Models\ModeloImpresora; // Cambiamos el modelo a ModeloImpresora
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Alert;

class ModeloImpresoraListLayout extends Table // Cambiamos el nombre de la clase
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'modelo_impresoras'; // Cambiamos el target para que coincida con la variable de la vista

    /**
     * Método para eliminar un modelo de impresora.
     */
    public function remove($id)
    {
        // Verifica permisos para eliminar
        if (!auth()->user()->hasAccess('platform.modelos_impresora.delete')) { // Cambiamos el permiso
            Alert::error('No tienes permisos para eliminar este modelo de impresora.');
            return redirect()->route('platform.modelos_impresora.list'); // Cambiamos la ruta
        }

        // Eliminar modelo de impresora
        ModeloImpresora::findOrFail($id)->delete(); // Cambiamos el modelo

        Alert::success('Modelo de impresora eliminado exitosamente.');
        return redirect()->route('platform.modelos_impresora.list'); // Cambiamos la ruta
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
                ->render(function (ModeloImpresora $modelo) { // Cambiamos el parámetro a ModeloImpresora
                    return Link::make($modelo->nombre) // Cambiamos la variable a $modelo
                        ->route('platform.modelos_impresora.edit', $modelo->id); // Cambiamos la ruta
                }),

            TD::make('marca_id', 'Marca')
                ->render(function (ModeloImpresora $modelo) { // Cambiamos el parámetro a ModeloImpresora
                    return $modelo->marca->nombre; // Cambiamos la variable a $modelo
                }),

            TD::make('descripcion', 'Descripción'),

            TD::make('created_at', 'Creado'),

            TD::make('updated_at', 'Última edición'),

            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (ModeloImpresora $modelo) { // Cambiamos el parámetro a ModeloImpresora
                    return auth()->user()->hasAccess('platform.modelos_impresora.edit') 
                        ? Link::make('Editar')
                            ->route('platform.modelos_impresora.edit', $modelo->id) // Cambiamos la ruta
                            ->icon('pencil')
                        : null;
                }),

            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (ModeloImpresora $modelo) { // Cambiamos el parámetro a ModeloImpresora
                    return auth()->user()->hasAccess('platform.modelos_impresora.delete') 
                        ? Button::make('Eliminar')
                            ->icon('trash')
                            ->confirm(__('¿Estás seguro de que deseas eliminar este modelo de impresora?')) // Cambiamos el texto
                            ->method('remove', ['id' => $modelo->id]) // Cambiamos la variable a $modelo
                        : null;
                }),
        ];
    }
}
