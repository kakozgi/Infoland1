<?php

namespace App\Orchid\Layouts;

use App\Models\Impresora;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Alert;

class ImpresoraListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'impresoras';

    /**
     * Método para eliminar una impresora.
     */
    public function remove($id)
    {
        // Verifica permisos para eliminar
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
     * Definición de columnas.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('serial', 'Serial')
                ->render(function (Impresora $impresora) {
                    return Link::make($impresora->serial)
                        ->route('platform.impresoras.edit', $impresora->id);
                }),
    
            TD::make('modelo', 'Modelo')
                ->render(function (Impresora $impresora) {
                    return $impresora->modeloImpresora->nombre;
                }),
    
                TD::make('contrato_id', 'Contrato')
                ->render(function (Impresora $impresora) {
                    return $impresora->contrato 
                        ? $impresora->contrato->numero_contrato 
                        : '<span style="color: red;">sin contrato</span>';
                }),
            
    
            TD::make('ubicacion', 'Ubicación'),
    
            TD::make('estado', 'Estado')
            ->filter(TD::FILTER_SELECT, [
                'contrato' => 'Contrato',
                'disponible' => 'Disponible',
                'servicio_tecnico' => 'Servicio Técnico',
                'desarme' => 'Desarme',
                'recambio' => 'Recambio',
            ])
            
                ->render(function (Impresora $impresora) {
                    // Asignar clases CSS según el estado
                    $estado = $impresora->estado;
                    $class = '';
                    
                    switch ($estado) {
                        case 'contrato':
                            $class = 'bg-success text-white'; // Verde
                            break;
                        case 'disponible':
                            $class = 'bg-primary text-white'; // Azul
                            break;
                        case 'servicio_tecnico':
                            $class = 'bg-warning text-dark'; // Amarillo
                            break;
                        case 'desarme':
                            $class = 'bg-danger text-white'; // Rojo
                            break;
                        case 'recambio':
                            $class = 'bg-info text-white'; // Azul claro
                            break;
                        default:
                            $class = 'bg-secondary text-white'; // Gris si no está en el estado esperado
                    }
    
                    return "<span class='badge {$class}'>" . ucfirst($estado) . "</span>";

                
                })
            ,
                
                
    
            TD::make('contador_actual', 'Contador Actual'),
    
            TD::make('created_at', 'Creado'),
    
            TD::make('updated_at', 'Última edición'),
    
            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (Impresora $impresora) {
                    return auth()->user()->hasAccess('platform.impresoras.edit') 
                        ? Link::make('Editar')
                            ->route('platform.impresoras.edit', $impresora->id)
                            ->icon('pencil')
                        : null;
                }),
    
            TD::make('Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render(function (Impresora $impresora) {
                    return auth()->user()->hasAccess('platform.impresoras.delete') 
                        ? Button::make('Eliminar')
                            ->icon('trash')
                            ->confirm(__('¿Estás seguro de que deseas eliminar esta impresora?'))
                            ->method('remove', ['id' => $impresora->id])
                        : null;
                }),
        ];
    }
}