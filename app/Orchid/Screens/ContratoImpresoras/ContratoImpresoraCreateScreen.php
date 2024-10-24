<?php

namespace App\Orchid\Screens\ContratoImpresoras;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Toast;
use App\Models\ContratoImpresora;
use App\Models\Contrato;
use App\Models\Impresora;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Relation;

class ContratoImpresoraCreateScreen extends Screen
{
    public function permission(): ?array
    {
        return ['platform.contratosImpresoras.create'];
    }

    public function name(): string
    {
        return 'Crear Contrato de Impresora';
    }

    public function commandBar(): array
    {
        return [
            Button::make('Guardar')
                ->method('save'),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Relation::make('contratoImpresora.contrato_id')
                    ->title('Contrato')
                    ->fromModel(Contrato::class, 'numero_contrato')
                    ->required()
                    ->help('Selecciona un contrato para filtrar impresoras')
                    ->listen('asyncLoadImpresoras'),

                Relation::make('contratoImpresora.impresora_id')
                    ->title('Impresora')
                    ->fromModel(Impresora::class, 'serial')  // Usar fromModel para la relación
                    ->dependsOn('contratoImpresora.contrato_id')  // Depender del contrato seleccionado
                    ->required(),

                Input::make('contratoImpresora.copias_minimas')
                    ->title('Copias Mínimas')
                    ->type('number')
                    ->required(),
            ]),
        ];
    }

    public function query(): array
    {
        return [
            'contratoImpresora.contrato_id' => Contrato::pluck('numero_contrato', 'id'),
            'contratoImpresora.impresora_id' => [],  // Vacío hasta que se seleccione un contrato
        ];
    }

    public function asyncLoadImpresoras($contrato_id)
    {
        // Obtener impresoras relacionadas con el contrato seleccionado
        $impresoras = Impresora::where('contrato_id', $contrato_id)->pluck('serial', 'id');

        return [
            'contratoImpresora.impresora_id' => $impresoras,
        ];
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'contratoImpresora.contrato_id' => 'required|exists:contratos,id',
            'contratoImpresora.impresora_id' => 'required|exists:impresoras,id',
            'contratoImpresora.copias_minimas' => 'required|integer|min:0',
        ]);

        ContratoImpresora::create($validated['contratoImpresora']);

        Toast::info('Contrato de impresora creado correctamente.');
    }
}
