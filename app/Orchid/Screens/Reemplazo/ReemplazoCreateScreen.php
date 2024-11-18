<?php

namespace App\Orchid\Screens\Reemplazo;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Toast;
use App\Models\Reemplazo;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Select;
use App\Models\Impresora;
use App\Models\ContratoImpresora;
use Orchid\Screen\Fields\Datetimer;

class ReemplazoCreateScreen extends Screen
{
    public function permission(): ?array
    {
        return ['platform.reemplazos.create'];
    }

    public function name(): string
    {
        return 'Crear Reemplazo';
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
                Select::make('reemplazo.id_impresora_original')
                    ->title('Impresora Original')
                    ->placeholder('Seleccione la impresora original')
                    ->fromQuery(Impresora::contratadas(), 'serial', 'id')
                    ->required(),

                Select::make('reemplazo.id_impresora_reemplazo')
                    ->title('Impresora de Reemplazo')
                    ->placeholder('Seleccione la impresora de reemplazo')
                    ->fromQuery(Impresora::disponibles(), 'serial', 'id')
                    ->required(),

                Datetimer::make('reemplazo.fecha_reemplazo')
                    ->title('Fecha de Reemplazo')
                    ->placeholder('Ingrese la fecha del reemplazo')
                    ->required()
                    ->type('date'),

                Datetimer::make('reemplazo.fecha_reactivacion')
                    ->title('Fecha de Reactivación')
                    ->placeholder('Ingrese la fecha de reactivación')
                    ->type('date'),
            ]),
        ];
    }

    public function query(): array
    {
        return [];
    }

    public function save(Request $request)
    {
        $idImpresoraOriginal = $request->input('reemplazo.id_impresora_original');
        $idImpresoraReemplazo = $request->input('reemplazo.id_impresora_reemplazo');
    
        // Obtener impresoras de la base de datos
        $impresoraOriginal = Impresora::findOrFail($idImpresoraOriginal);
        $impresoraReemplazo = Impresora::findOrFail($idImpresoraReemplazo);
    
        // Obtener contrato de la impresora original
        $contratoIdOriginal = $impresoraOriginal->contrato_id;
    
        // Determinar contadores inicial y final
        $contadorInicial = $impresoraOriginal->ultimoHistorial()->contador ?? 0; // Último historial
        $contadorFinal = $impresoraOriginal->contador_actual; // Contador actual de la tabla `impresoras`
    
        // Crear el registro en la tabla `reemplazos`
        Reemplazo::create([
            'id_impresora_original' => $idImpresoraOriginal,
            'id_impresora_reemplazo' => $idImpresoraReemplazo,
            'fecha_reemplazo' => $request->input('reemplazo.fecha_reemplazo'),
            'fecha_reactivacion' => $request->input('reemplazo.fecha_reactivacion'),
            'numero_contrato' => $contratoIdOriginal,
            'contador_inicial' => $contadorInicial, // Último historial
            'contador_final' => $contadorFinal,     // Contador actual
        ]);
    
        // Actualizar estados de las impresoras
        $impresoraOriginal->update([
            'contrato_id' => null,
            'estado' => 'recambio', // Marca como en recambio
        ]);
    
        $impresoraReemplazo->update([
            'contrato_id' => $contratoIdOriginal ?? 0, // Asigna el contrato de la original
            'estado' => 'contrato', // Marca como activa en contrato
        ]);
    
        // Asociar las configuraciones de `copias_minimas`
        $contratoImpresoraOriginal = ContratoImpresora::where('contrato_id', $contratoIdOriginal)
            ->where('impresora_id', $idImpresoraOriginal)
            ->first();
    
        if ($contratoImpresoraOriginal) {
            ContratoImpresora::create([
                'contrato_id' => $contratoIdOriginal,
                'impresora_id' => $idImpresoraReemplazo,
                'copias_minimas' => $contratoImpresoraOriginal->copias_minimas,
            ]);
        }
    
        Toast::info('Reemplazo creado correctamente con contadores inicial y final guardados.');
    }
} 