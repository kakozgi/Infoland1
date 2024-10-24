<?php 

namespace App\Orchid\Screens\Cliente;

use App\Models\Cliente;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Toast;

class ClienteEditScreen extends Screen
{
    public $cliente;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Cliente $cliente): array
    {
        return [
            'cliente' => $cliente,
        ];
    }

      /**
    * Permission required to view this screen.
    *
    * @return array|null
    */
    public function permission(): ?array // Cambiar a array
    {
        return ['platform.clientes.edit']; // Permiso requerido para editar clientes
    }

    /**
     * Display header name.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Editar Cliente';
    }

    /**
     * Button commands.
     *
     * @return Button[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Guardar')
                ->method('save'),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('cliente.nombre')
                    ->title('Nombre')
                    ->require(),

                Input::make('cliente.rut')
                    ->title('RUT')
                    ->require(),

                Input::make('cliente.correo')
                    ->title('Correo'),

                Input::make('cliente.telefono')
                    ->title('Teléfono'),

                Input::make('cliente.direccion')
                    ->title('Dirección')
                    ->require(),
            ]),
        ];
    }

    /**
     * Save the cliente.
     *
     * @param Cliente $cliente
     * @param Request $request
     */
    public function save(Cliente $cliente, Request $request)
    {
        $cliente->fill($request->get('cliente'))->save();

        Toast::info('Cliente actualizado correctamente.');
    }
}
