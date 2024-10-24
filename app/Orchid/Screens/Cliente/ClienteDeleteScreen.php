<?php


namespace App\Orchid\Screens\Cliente;

use App\Models\Cliente; // Asegúrate de tener el modelo Cliente importado
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Support\Facades\Alert;
use Illuminate\Http\Request;

class ClienteDeleteScreen extends Screen
{
    public function name(): ?string
    {
        return 'Eliminar Cliente';
    }

    public function permission(): array
    {
        return [
            'platform.clientes.delete', // Asegúrate de que este permiso esté definido
        ];
    }

    public function query(Cliente $cliente): array
    {
        return [
            'cliente' => $cliente,
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Eliminar')
                ->method('remove')
                ->confirm(__('¿Estás seguro de que deseas eliminar este cliente?')),
        ];
    }

    public function remove(Cliente $cliente)
    {
        $cliente->delete();
        Alert::success('Cliente eliminado exitosamente.');
        return redirect()->route('platform.clientes.list'); // Redirige a la lista de clientes
    }

    public function layout(): array
    {
        return [];
    }
}
