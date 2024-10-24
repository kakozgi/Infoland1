<?php

namespace App\Orchid\Screens\HistorialContadores;

use App\Models\HistorialContador;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\HistorialContadorListLayout;
use Orchid\Support\Facades\Alert;

class HistorialContadorListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'historiales' => HistorialContador::with('impresora') // Obtener los historiales con la relación de impresoras
                ->paginate(),
        ];
    }

    /**
     * Permisos necesarios para ver este Screen.
     *
     */
    public function permission(): ?iterable
    {
        return [
            'platform.historiales.view',
        ];
    }

    /**
     * El nombre que se muestra en la pantalla y en los encabezados.
     *
     */
    public function name(): ?string
    {
        return 'Historial de Contadores';
    }

    /**
     * La descripción que se muestra en la pantalla debajo del encabezado.
     *
     */
    public function description(): ?string
    {
        return 'Lista de todos los historiales de contadores de impresoras';
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return auth()->user()->hasAccess('platform.historiales.create')
            ? [
                Link::make('Registrar nuevo historial')
                    ->icon('plus')
                    ->route('platform.historiales.create'),
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
            HistorialContadorListLayout::class,
        ];
    }
}
