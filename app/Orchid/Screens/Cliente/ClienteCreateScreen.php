<?php

namespace App\Orchid\Screens\Cliente;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input; // Asegúrate de importar Input
use Orchid\Support\Facades\Toast; // Asegúrate de importar Toast
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteCreateScreen extends Screen
{
    /**
    * Permission required to view this screen.
    *
    * @return array|null
    */
   public function permission(): ?array // Cambiar a array
   {
       return ['platform.clientes.create']; // Permiso requerido para crear clientes
   }

    /**
     * Display header name.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Crear Cliente';
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
                    ->require()   ,            

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
     * Query data (required by Screen).
     *
     * @return array
     */
    public function query(): array
    {
        return []; // Retorna un array vacío ya que no necesitas datos para esta vista
    }


    /**
     * Save the new cliente.
     *
     * @param Request $request
     */
    public function save(Request $request)
    {


        $validated = $request->validate([
            'cliente.nombre' => 'required|string|max:255',
            'cliente.rut' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clientes', 'rut'), // Verifica que el RUT sea único
            ],
           
            ], 
            
        );


        Cliente::create($request->get('cliente'));

        Toast::info('Cliente creado correctamente.');
    }
}
