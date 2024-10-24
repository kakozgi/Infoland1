<?php

namespace App\Orchid\Filters;

use Orchid\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Screen\Fields\Select;
use App\Models\Cliente;

class ClienteFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = ['cliente'];

    /**
     * Display filter field.
     *
     * @return array
     */
    public function display(): array
    {
        return [
            Select::make('cliente')
                ->fromModel(Cliente::class, 'nombre') // Nombre del cliente
                ->empty('Selecciona un Cliente', 0)
                ->value($this->request->get('cliente')) // Mantener la selección actual
                ->title('Filtrar por Cliente')
                ->submitOnChange(), // Esto recargará la página con el cliente seleccionado
        ];
    }

    /**
     * Apply the filter to the query.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        // Filtrar a través de la relación contrato.cliente
        return $builder->whereHas('contrato.cliente', function ($query) {
            $query->where('id', $this->request->get('cliente'));
        });
    }
}
