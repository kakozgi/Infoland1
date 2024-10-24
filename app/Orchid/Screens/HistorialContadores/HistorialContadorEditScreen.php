<?php

namespace App\Orchid\Screens\HistorialContadores;

use App\Models\HistorialContador;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Support\Facades\Toast;

class HistorialContadorEditScreen extends Screen
{
    public $historialContador; // Cambiado a historialContador

    /**
     * Query data.
     *
     * @return array
     */
    public function query(HistorialContador $historialContador): array
    {
       // dd($historialContador); // Esto detendrá la ejecución y mostrará el objeto
        return [
            'historialContador' => $historialContador,
        ];
    }
    

    /**
     * Permiso requerido para ver este Screen.
     *
     * @return array|null
     */
//    / public function permission(): ?array
//     {
//         //return ['platform.historiales.edit']; // Permiso requerido para editar historiales de contadores
//     }

    /**
     * Nombre mostrado en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Editar Historial de Contadores'; // Cambiado el título
    }

    /**
     * Botones de la barra de comandos.
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
     * Layouts para el formulario de edición.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('historialContador.impresora_id') // Cambiado a historialContador
                    ->title('Impresora ID'),
                     
                Input::make('historialContador.contador') // Cambiado a historialContador
                    ->title('Contador Actual'),

                DateTimer::make('historialContador.fecha_registro') // Cambiado a historialContador
                    ->title('Fecha de Registro'),
            ]),
        ];
    }

    /**
     * Guarda los cambios en el historial de contadores.
     *
     * @param HistorialContador $historialContador
     * @param Request $request
     */
    public function save(HistorialContador $historialContador, Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'historialContador.contador' => 'required|integer|min:0', // Cambiado a historialContador
            'historialContador.fecha_registro' => 'required|date', // Cambiado a historialContador
        ]);

        // Actualizar el historial con los datos validados
        $historialContador->contador = $validated['historialContador']['contador']; // Cambiado a historialContador
        $historialContador->fecha_registro = $validated['historialContador']['fecha_registro']; // Cambiado a historialContador
        $historialContador->save();

        // Mostrar un mensaje de éxito
        Toast::info('Historial de contador actualizado correctamente.');
    }
}
