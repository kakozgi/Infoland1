<?php

namespace App\Orchid\Screens;

use App\Models\Cliente;
use App\Models\Impresora;
use App\Models\ContratoImpresora;
use Orchid\Screen\Screen;
use Orchid\Screen\Fields\Select;
use Illuminate\Http\Request;
use App\Orchid\Layouts\ImpresoraContadorLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Label;
use Orchid\Support\Facades\Layout;

class ImpresoraContadorScreen extends Screen
{
    public function name(): string
    {
        return 'Visualizar Impresoras por Clientes';
    }

    public function query(): array
    {
        $clienteSeleccionado = request()->get('cliente_id');
        $totalCosto = 0;
        $totalCopias = 0;
        $impresoras = collect();
        $diferenciasPorContrato = [];

        if ($clienteSeleccionado) {
            $impresoras = $this->obtenerImpresorasPorCliente($clienteSeleccionado);

            foreach ($impresoras as $impresora) {
                $contrato = $impresora->contrato;
                if (!$contrato) {
                    continue;  // Si no hay contrato, pasar a la siguiente impresora
                }
            
                $diferencia = $this->calcularDiferencia($impresora); 
                $numeroContrato = $contrato->numero_contrato;
                $totalCopias += $diferencia;

                if (!isset($diferenciasPorContrato[$numeroContrato])) {
                    $diferenciasPorContrato[$numeroContrato] = $this->inicializarDatosContrato($contrato);
                }

                if ($contrato->tipo_minimo === 'grupal') {
                    // Lógica para contratos grupales
                    $diferenciasPorContrato[$numeroContrato]['suma_diferencias'] += $diferencia;
                } elseif ($contrato->tipo_minimo === 'individual') {
                    // Lógica para contratos individuales
                    $diferenciasPorContrato[$numeroContrato]['suma_diferencias'] += $diferencia;

                    // Calcular el costo basado en la diferencia y el mínimo individual
                    $costoContrato = $this->calcularCostoIndividual($impresora, $diferencia, $contrato);
                    $diferenciasPorContrato[$numeroContrato]['costo_contrato'] += $costoContrato;
                } elseif ($contrato->tipo_minimo === 'directo') {
                    // Lógica para contratos directos
                    $diferenciasPorContrato[$numeroContrato]['suma_diferencias'] += $diferencia;

                    // Calcular el costo para el tipo directo
                    $costoContrato = $this->calcularCostoDirecto($impresora, $diferencia, $contrato);
                    $diferenciasPorContrato[$numeroContrato]['costo_contrato'] += $costoContrato;
                }
            }

            // Procesar los contratos para calcular los costos totales
            foreach ($diferenciasPorContrato as $numeroContrato => $datos) {
                if ($datos['tipo_minimo'] === 'grupal') {
                    $costoContrato = $this->calcularCostoGrupal($numeroContrato, $clienteSeleccionado, $datos);
                    $diferenciasPorContrato[$numeroContrato]['costo_contrato'] = $costoContrato;
                    $totalCosto += $costoContrato;
                } else {
                    $totalCosto += $datos['costo_contrato'];  // Acumular el costo individual y directo correctamente
                }
            }
        }
        
        return [
            'impresoras' => $impresoras,
            'clienteSeleccionado' => $clienteSeleccionado,
            'totalCosto' => $totalCosto,
            'totalCopias' => $totalCopias,
            'diferenciasPorContrato' => $diferenciasPorContrato,
        ];
    }

    // Encapsula la lógica para obtener impresoras asociadas al cliente
    private function obtenerImpresorasPorCliente($clienteSeleccionado)
    {
        return Impresora::join('contratos', 'impresoras.contrato_id', '=', 'contratos.id')
            ->where('contratos.cliente_id', $clienteSeleccionado)
            ->select('impresoras.*')
            ->orderByRaw('CASE WHEN impresoras.contrato_id IS NOT NULL THEN 0 ELSE 1 END')
            ->orderBy('contratos.numero_contrato')
            ->get();
    }

    // Encapsula la lógica para calcular la diferencia de copias
    private function calcularDiferencia(Impresora $impresora)
    {
        // Usar el método del modelo para obtener el último historial relevante
        $ultimoHistorial = $impresora->ultimoHistorial();
        $diferencia = $ultimoHistorial ? $impresora->contador_actual - $ultimoHistorial->contador : $impresora->contador_actual;

        return max(0, $diferencia); // Asegura que la diferencia no sea negativa
    }

    // Encapsula la inicialización de datos por contrato
    private function inicializarDatosContrato($contrato)
    {
        return [
            'numero_contrato' => $contrato->numero_contrato,
            'tipo_minimo' => $contrato->tipo_minimo,
            'valor_por_copia' => $contrato->valor_por_copia,
            'suma_diferencias' => 0,
            'costo_contrato' => 0,
        ];
    }

    // Cálculo del costo para contratos individuales
    private function calcularCostoIndividual(Impresora $impresora, $diferencia, $contrato)
    {
        $contratoImpresora = ContratoImpresora::where('contrato_id', $contrato->id)
            ->where('impresora_id', $impresora->id)
            ->first();
    
        $copiasMinimas = $contratoImpresora ? $contratoImpresora->copias_minimas : 0;

        if ($diferencia < $copiasMinimas) {
            $costoContrato = $copiasMinimas * $contrato->valor_por_copia;
        } else {
            $costoContrato = $diferencia * $contrato->valor_por_copia;
        }

        return $costoContrato;
    }

    // Cálculo del costo para contratos directos
    private function calcularCostoDirecto(Impresora $impresora, $diferencia, $contrato)
    {
        return $diferencia * $contrato->valor_por_copia; // Cálculo simple sin copias mínimas
    }

    // Cálculo del costo para contratos grupales
    private function calcularCostoGrupal($numeroContrato, $clienteSeleccionado, $datos)
    {
        $sumaDiferencias = $datos['suma_diferencias'];
    
        $contrato = Impresora::join('contratos', 'impresoras.contrato_id', '=', 'contratos.id')
            ->where('contratos.numero_contrato', $numeroContrato)
            ->where('contratos.cliente_id', $clienteSeleccionado)
            ->select('contratos.*')
            ->first();

        $minimoGrupal = $contrato->copias_minimas;

        if ($sumaDiferencias < $minimoGrupal) {
            $costoContrato = $minimoGrupal * $datos['valor_por_copia'];
        } else {
            $costoContrato = $sumaDiferencias * $datos['valor_por_copia'];
        }

        return $costoContrato;
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Select::make('cliente_id')
                    ->fromModel(Cliente::class, 'nombre')
                    ->empty('Selecciona un Cliente', 0)
                    ->title('Selecciona un Cliente')
                    ->help('Este filtro muestra las impresoras del cliente seleccionado')
                    ->value(request('cliente_id') ?? $this->query()['clienteSeleccionado']),

                Button::make('Confirmar Selección')
                    ->icon('check')
                    ->method('actualizarPagina')
                    ->style('background-color: #28a745; color: white;'),
            ]),

            ImpresoraContadorLayout::class,

            Layout::rows(array_map(function ($contrato) {
                return $contrato['tipo_minimo'] === 'individual' ?
                    Label::make('individual_' . $contrato['numero_contrato'])
                        ->value('Contrato: ' . $contrato['numero_contrato'] . ' - Copias Totales: ' . $contrato['suma_diferencias'] . ' - Costo: $' . number_format($contrato['costo_contrato'], 0, '', '.'))
                        ->style('font-weight: bold; margin-top: 10px;')
                        ->title('Contrato Individual') :
                    Label::make('costo_contrato_' . $contrato['numero_contrato'])
                        ->value('Contrato: ' . $contrato['numero_contrato'] . ' - Diferencia Total: ' . $contrato['suma_diferencias'] . ' - Costo: $' . number_format($contrato['costo_contrato'], 0, '', '.'))
                        ->title('Diferencias y Costo por Contrato')
                        ->style('font-weight: bold; margin-top: 10px;');
            }, $this->query()['diferenciasPorContrato'])),

            Layout::rows([
                Label::make('totalCosto')
                    ->value(function () {
                        $totalCosto = $this->query()['totalCosto'];
                        return is_numeric($totalCosto) ? 'Costo Total: $' . number_format($totalCosto, 0, '', '.') : 'Costo Total: No disponible';
                    })
                    ->title('Resumen Costo Total')
                    ->style('font-weight: bold; margin-top: 10px;'),
                    
                Label::make('totalCopias')
                    ->value(function () {
                        $totalCopias = $this->query()['totalCopias'];
                        return 'Copias Totales: ' . number_format($totalCopias, 0, '', '.');
                    })
                    ->title('Resumen Total de Copias')
                    ->style('font-weight: bold; margin-top: 10px;'),
            ]),
        ];
    }

    public function actualizarPagina(Request $request)
    {
        $clienteSeleccionado = $request->get('cliente_id');

        return redirect()->route('platform.impresoras_contador', [
            'cliente_id' => $clienteSeleccionado
        ])->withInput();
    }
}
