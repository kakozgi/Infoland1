<?php

namespace App\Orchid\Screens\ContratoImpresoras;

use App\Models\ContratoImpresora;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Toast;

class ContratoImpresoraEditScreen extends Screen
{
    public $contratoImpresora;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(ContratoImpresora $contratoImpresora): array
    {
        return [
            'contratoImpresora' => $contratoImpresora,
        ];
    }

    /**
     * Permiso requerido para ver este Screen.
     *
     * @return array|null
     */
    public function permission(): ?array
    {
        return ['platform.contratosImpresoras.edit']; // Permiso requerido para editar contratos de impresoras
    }

    /**
     * Nombre mostrado en el encabezado de la pantalla.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Editar Contrato de Impresora';
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
                Input::make('contratoImpresora.contrato_id')
                    ->title('ID del Contrato')
                    ->type('number')
                    ->required(),

                Input::make('contratoImpresora.impresora_id')
                    ->title('ID de la Impresora')
                    ->type('number')
                    ->required(),

                Input::make('contratoImpresora.detalle')
                    ->title('Detalle del Contrato')
                    ->placeholder('Ingrese detalles adicionales sobre el contrato'),
            ]),
        ];
    }

    /**
     * Guarda los cambios en el contrato de impresora.
     *
     * @param ContratoImpresora $contratoImpresora
     * @param Request $request
     */
    public function save(ContratoImpresora $contratoImpresora, Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'contratoImpresora.contrato_id' => 'required|integer',
            'contratoImpresora.impresora_id' => 'required|integer',
            'contratoImpresora.detalle' => 'nullable|string|max:255',
        ]);

        // Actualizar el contrato de impresora con los datos validados
        $contratoImpresora->fill($validated['contratoImpresora'])->save();

        // Mostrar un mensaje de éxito
        Toast::info('Contrato de impresora actualizado correctamente.');
    }
}
