<?php

namespace App\Orchid\Screens;

use App\Models\Cliente;
use App\Models\Impresora;
use App\Models\Reemplazo;
use App\Models\ContratoImpresora;
use Orchid\Screen\Screen;
use Orchid\Screen\Fields\Select;
use Illuminate\Http\Request;
use App\Orchid\Layouts\ImpresoraContadorLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Label;
use Orchid\Support\Facades\Layout;
use App\Models\FacturaDetalle;
use App\Models\Factura;
use Carbon\Carbon;

class ImpresoraContadorScreen extends Screen
{
    public function name(): string
    {
        return 'Visualizar Impresoras por Clientes';
    }

    // public function query(): array
    // {
    //     $clienteSeleccionado = request()->get('cliente_id');
    //     $totalCosto = 0;
    //     $totalCopias = 0;
    //     $impresoras = collect();
    //     $diferenciasPorContrato = [];
        
    //     if ($clienteSeleccionado) {
    //         // Obtén las impresoras y reemplazos para el cliente seleccionado
    //         $impresoras = $this->obtenerImpresorasYReemplazosPorCliente($clienteSeleccionado);
        
    //         foreach ($impresoras as $item) {
    //             $impresora = $item['impresora'];
    //             $contrato = $impresora->contrato;
        
    //             if (!$contrato) continue;
        
    //             // Llama al método del modelo para obtener el contador anterior
    //             $contadorAnterior = $impresora->obtenerContadorAnterior();
    //             $diferencia = max(0, $impresora->contador_actual - $contadorAnterior);
    
    //             $totalCopias += $diferencia;
        
    //             $numeroContrato = $contrato->numero_contrato;
        
    //             if (!isset($diferenciasPorContrato[$numeroContrato])) {
    //                 $diferenciasPorContrato[$numeroContrato] = $this->inicializarDatosContrato($contrato);
    //                 $diferenciasPorContrato[$numeroContrato]['impresoras'] = [];
    //             }
        
    //             $contratoImpresora = ContratoImpresora::where('contrato_id', $contrato->id)
    //                 ->where('impresora_id', $impresora->id)
    //                 ->first();
    //             $copiasMinimas = $contratoImpresora ? $contratoImpresora->copias_minimas : 0;
        
    //             $costoImpresora = $this->calcularCostoIndividual($impresora, $diferencia, $contrato);
    
    //             $diferenciasPorContrato[$numeroContrato]['impresoras'][] = [
    //                 'id' => $impresora->id,
    //                 'contador_anterior' => $contadorAnterior,
    //                 'diferencia' => $diferencia,
    //                 'copias_minimas' => $copiasMinimas,
    //                 'costo_calculado' => $costoImpresora,
    //                 'es_reemplazo' => $item['es_reemplazo'] ?? false,
    //                 'impresora_original_serial' => $item['impresora_original_serial'] ?? null,
    //             ];
    
    //             $diferenciasPorContrato[$numeroContrato]['suma_diferencias'] += $diferencia;
        
    //             if ($contrato->tipo_minimo === 'individual') {
    //                 $diferenciasPorContrato[$numeroContrato]['costo_contrato'] += $costoImpresora;
    //             }
    //         }
    
    //         // Cálculo de costos grupales
    //         foreach ($diferenciasPorContrato as &$datosContrato) {
    //             if ($datosContrato['tipo_minimo'] === 'grupal') {
    //                 $datosContrato['costo_contrato'] = $this->calcularCostoGrupal(
    //                     $datosContrato['numero_contrato'],
    //                     $clienteSeleccionado,
    //                     $datosContrato
    //                 );
    //             }
    //             $totalCosto += $datosContrato['costo_contrato'];
    //         }
    //     }
    
    //     $impresorasData = $impresoras->map(function ($item) {
    //         $impresora = $item['impresora'];
    //         $contadorAnterior = $impresora->obtenerContadorAnterior();
    //         $diferencia = max(0, $impresora->contador_actual - $contadorAnterior);
    
    //         return [
    //             'impresora' => $impresora,
    //             'es_reemplazo' => $item['es_reemplazo'] ?? false,
    //             'impresora_original_serial' => $item['impresora_original_serial'] ?? null,
    //             'contador_anterior' => $contadorAnterior,
    //             'diferencia' => $diferencia,
    //         ];
    //     });
    
    //     return [
    //         'impresoras' => $impresorasData,
    //         'clienteSeleccionado' => $clienteSeleccionado,
    //         'totalCosto' => $totalCosto,
    //         'totalCopias' => $totalCopias,
    //         'diferenciasPorContrato' => $diferenciasPorContrato,
    //     ];
    // }
    public function query(): array
    {
        $clienteSeleccionado = request()->get('cliente_id');
        $totalCosto = 0;
        $totalCopias = 0;
        $impresoras = collect();
        $impresoras = Impresora::with('contrato')->get();
        $diferenciasPorContrato = [];
    
        if ($clienteSeleccionado) {
            $impresoras = $this->obtenerImpresorasYReemplazosPorCliente($clienteSeleccionado);


    
            foreach ($impresoras as $item) {
                if (!isset($item['impresora'])) {
                    continue;
                }
    
                $impresora = $item['impresora'];
                $datosCongelados = $item['datos_congelados'] ?? null;
    
                $contadorInicial = $datosCongelados['contador_inicial'] ?? $impresora->obtenerUltimoContador();
                $contadorFinal = $datosCongelados['contador_final'] ?? $impresora->obtenerContadorActual();
    
                $contrato = $impresora->contrato;
    
                if (!$contrato) {
                    continue;
                }
    
                $diferencia = max(0, $contadorFinal - $contadorInicial);
                $totalCopias += $diferencia;
                $numeroContrato = $contrato->numero_contrato;
    
                if (!isset($diferenciasPorContrato[$numeroContrato])) {
                    $diferenciasPorContrato[$numeroContrato] = $this->inicializarDatosContrato($contrato);
                    $diferenciasPorContrato[$numeroContrato]['impresoras'] = [];
                }
    
                $diferenciasPorContrato[$numeroContrato]['impresoras'][] = [
                    'id' => $impresora->id,
                    'serial' => $impresora->serial,
                    'contador_inicial' => $contadorInicial,
                    'contador_final' => $contadorFinal,
                    'diferencia' => $diferencia,
                    'copias_minimas' => $contrato->copias_minimas ?? 0,
                    'es_reemplazo' => $item['es_reemplazo'] ?? false,
                    'impresora_original_serial' => $item['impresora_original_serial'] ?? null,
                ];
    
                $diferenciasPorContrato[$numeroContrato]['suma_diferencias'] += $diferencia;
    
                if ($contrato->tipo_minimo === 'individual') {
                    $diferenciasPorContrato[$numeroContrato]['costo_contrato'] += $this->calcularCostoIndividual($impresora, $diferencia, $contrato);
                }
            }
    
            foreach ($diferenciasPorContrato as &$datosContrato) {
                if ($datosContrato['tipo_minimo'] === 'grupal') {
                    $datosContrato['costo_contrato'] = $this->calcularCostoGrupal(
                        $datosContrato['numero_contrato'],
                        $clienteSeleccionado,
                        $datosContrato
                    );
                }
                $totalCosto += $datosContrato['costo_contrato'];
            }
        }

        // dd([
        //     'impresoras' => $impresoras->map(...),
        //     'clienteSeleccionado' => $clienteSeleccionado,
        //     'totalCosto' => $totalCosto,
        //     'totalCopias' => $totalCopias,
        //     'diferenciasPorContrato' => $diferenciasPorContrato,
        // ]);
        
    
        return [
            'impresoras' => $impresoras->map(function ($item) {
                if (!isset($item['impresora'])) {
                    return [
                        'serial' => '<span class="text-danger">Sin datos</span>',
                        'contador_inicial' => 0,
                        'contador_final' => 0,
                        'diferencia' => 0,
                    ];
                }
    
                $impresora = $item['impresora'];
                return [
                    'serial' => $impresora->serial ?? 'Sin serial',
                    'es_reemplazo' => $item['es_reemplazo'] ?? false,
                    'impresora_original_serial' => $item['impresora_original_serial'] ?? null,
                    'contador_inicial' => $item['datos_congelados']['contador_inicial'] ?? $impresora->obtenerUltimoContador(),
                    'contador_final' => $item['datos_congelados']['contador_final'] ?? $impresora->obtenerContadorActual(),
                    'diferencia' => max(0, $item['datos_congelados']['contador_final'] ?? $impresora->obtenerContadorActual() - ($item['datos_congelados']['contador_inicial'] ?? $impresora->obtenerUltimoContador())),
                ];
            }),
            'clienteSeleccionado' => $clienteSeleccionado,
            'totalCosto' => $totalCosto,
            'totalCopias' => $totalCopias,
            'diferenciasPorContrato' => $diferenciasPorContrato,
            
        ];
    }
    
    
    private function obtenerContadorInicial(Impresora $impresora)
    {
        // Buscar si la impresora fue reemplazada como original
        $reemplazo = Reemplazo::where('id_impresora_original', $impresora->id)
            ->orderBy('fecha_reemplazo', 'desc')
            ->first();
    
        if ($reemplazo) {
            // Si existe un reemplazo, usa el contador inicial congelado
            return $reemplazo->contador_inicial ?? 0;
        }
    
        // Si no hay reemplazo, usa el último historial o el contador actual
        return $impresora->ultimoHistorial()->contador ?? $impresora->contador_actual ?? 0;
    }
    
    private function obtenerContadorFinal(Impresora $impresora)
    {
        // Buscar si esta impresora es un reemplazo activo
        $reemplazo = Reemplazo::where('id_impresora_reemplazo', $impresora->id)
            ->orderBy('fecha_reemplazo', 'desc')
            ->first();
    
        if ($reemplazo) {
            // Si es un reemplazo, usa el contador final congelado
            return $reemplazo->contador_final ?? 0;
        }
    
        // Si no es un reemplazo, usa el contador actual
        return $impresora->contador_actual ?? 0;
    }
    

    public function guardarFacturacion(Request $request)
    {
        $clienteSeleccionado = $this->query()['clienteSeleccionado'];
        $totalCosto = $this->query()['totalCosto'];

        $factura = Factura::create([
            'contrato_id' => $clienteSeleccionado,
            'fecha_factura' => Carbon::now(),
            'valor_total' => $totalCosto,
        ]);

        foreach ($this->query()['diferenciasPorContrato'] as $contrato) {
            foreach ($contrato['impresoras'] as $impresora) {
                FacturaDetalle::create([
                    'factura_id' => $factura->id,
                    'contrato_id' => $clienteSeleccionado,
                    'impresora_id' => $impresora['id'],
                    'diferencia_copias' => $impresora['diferencia'],
                    'copias_minimas' => $impresora['copias_minimas'],
                    'costo_por_copia' => $contrato['valor_por_copia'],
                    'costo_calculado' => $impresora['costo_calculado'],
                ]);
            }
        }

        return redirect()->route('platform.impresoras_contador')
            ->with('success', 'La facturación se ha confirmado y guardado correctamente.');
    }

    private function obtenerImpresorasYReemplazosPorCliente($clienteSeleccionado)
    {
        $impresoras = Impresora::with('contrato')
            ->whereHas('contrato', function ($query) use ($clienteSeleccionado) {
                $query->where('cliente_id', $clienteSeleccionado);
            })
            ->get();
    
        $idsImpresoras = $impresoras->pluck('id');
    
        $reemplazos = Reemplazo::whereIn('id_impresora_reemplazo', $idsImpresoras)
            ->orWhereIn('id_impresora_original', $idsImpresoras)
            ->get();
    
        $impresorasConReemplazos = collect();
    
        foreach ($impresoras as $impresora) {
            $reemplazo = $reemplazos->firstWhere('id_impresora_reemplazo', $impresora->id);
    
            if ($reemplazo) {
                $impresoraOriginal = Impresora::find($reemplazo->id_impresora_original);
                $impresorasConReemplazos->push([
                    'impresora' => $impresoraOriginal ?? $impresora,
                    'es_reemplazo' => true,
                    'impresora_original_serial' => $impresoraOriginal ? $impresoraOriginal->serial : null,
                    'datos_congelados' => [
                        'contador_inicial' => $reemplazo->contador_inicial,
                        'contador_final' => $reemplazo->contador_final,
                        'fecha_reemplazo' => $reemplazo->fecha_reemplazo,
                    ],
                ]);
            } else {
                $impresorasConReemplazos->push([
                    'impresora' => $impresora,
                    'es_reemplazo' => false,
                    'impresora_original_serial' => null,
                    'datos_congelados' => null,
                ]);
            }
        }


    
        return $impresorasConReemplazos;
    }
    
    private function inicializarDatosContrato($contrato)
    {
        return [
            'numero_contrato' => $contrato->numero_contrato,
            'tipo_minimo' => $contrato->tipo_minimo,
            'valor_por_copia' => $contrato->valor_por_copia,
            'copias_minimas' => $contrato->copias_minimas,
            'suma_diferencias' => 0,
            'costo_contrato' => 0,
        ];
    }
    
    private function calcularCostoIndividual(Impresora $impresora, $diferencia, $contrato)
    {
        $contratoImpresora = ContratoImpresora::where('contrato_id', $contrato->id)
            ->where('impresora_id', $impresora->id)
            ->first();

        $copiasMinimas = $contratoImpresora ? $contratoImpresora->copias_minimas : 0;

        return $diferencia < $copiasMinimas
            ? $copiasMinimas * $contrato->valor_por_copia
            : $diferencia * $contrato->valor_por_copia;
    }

    private function calcularCostoDirecto(Impresora $impresora, $diferencia, $contrato)
    {
        return $diferencia * $contrato->valor_por_copia;
    }

    private function calcularCostoGrupal($numeroContrato, $clienteSeleccionado, $datos)
    {
        $sumaDiferencias = $datos['suma_diferencias'];
        $contrato = Impresora::join('contratos', 'impresoras.contrato_id', '=', 'contratos.id')
            ->where('contratos.numero_contrato', $numeroContrato)
            ->where('contratos.cliente_id', $clienteSeleccionado)
            ->select('contratos.*')
            ->first();
    
        $minimoGrupal = $contrato->copias_minimas;
        return $sumaDiferencias < $minimoGrupal
            ? $minimoGrupal * $datos['valor_por_copia']
            : $sumaDiferencias * $datos['valor_por_copia'];
    }
    
    public function layout(): array
    {
        $layout = [
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
        ];

        foreach ($this->query()['diferenciasPorContrato'] as $contrato) {
            $rows = [
                Label::make('contrato_' . $contrato['numero_contrato'])
                    ->value('Contrato: ' . $contrato['numero_contrato'] 
                            . ' - Tipo de Contrato: ' . ucfirst($contrato['tipo_minimo']))
                    ->style('font-weight: bold; font-size: 16px; color: #2C3E50; margin-top: 10px;'),

                Label::make('detalleContrato_' . $contrato['numero_contrato'])
                    ->value($contrato['tipo_minimo'] === 'individual' 
                            ? 'Copias Totales: ' . number_format($contrato['suma_diferencias'], 0, '', '.') 
                              . ' - Costo Total del Contrato: $' . number_format($contrato['costo_contrato'], 0, '', '.')
                            : 'Diferencia Total (para mínimo grupal): ' . number_format($contrato['suma_diferencias'], 0, '', '.')
                              . ' - Costo: $' . number_format($contrato['costo_contrato'], 0, '', '.'))
                    ->style('margin-top: 5px; color: #34495E;'),
            ];

            if ($contrato['tipo_minimo'] === 'individual' && $contrato['suma_diferencias'] < $contrato['copias_minimas']) {
                $rows[] = Label::make('notaMinimo_' . $contrato['numero_contrato'])
                    ->value('* El costo se basa en el mínimo de copias establecido en el contrato.')
                    ->style('font-style: italic; color: #E74C3C;');
            }

            $layout[] = Layout::rows($rows)->title('Detalles del Contrato ' . $contrato['numero_contrato']);
        }

        $layout[] = Layout::rows([
            Label::make('totalCosto')
                ->value(function () {
                    $totalCosto = $this->query()['totalCosto'];
                    return is_numeric($totalCosto) 
                        ? 'Costo Total de Todos los Contratos: $' . number_format($totalCosto, 0, '', '.')
                        : 'Costo Total: No disponible';
                })
                ->title('Resumen Costo Total')
                ->style('font-weight: bold; font-size: 18px; margin-top: 20px; color: #2C3E50;'),

            Label::make('totalCopias')
                ->value(function () {
                    $totalCopias = $this->query()['totalCopias'];
                    return 'Copias Totales de Todos los Contratos: ' . number_format($totalCopias, 0, '', '.');
                })
                ->title('Resumen Total de Copias')
                ->style('font-weight: bold; font-size: 18px; color: #34495E; margin-top: 5px;'),
        ]);

        $layout[] = Layout::rows([
            Button::make('Confirmar Facturación')
                ->icon('check')
                ->method('guardarFacturacion')
                ->style('background-color: #007bff; color: white; margin-top: 20px;')
                ->confirm('¿Estás seguro de que deseas confirmar la facturación? Esto guardará los datos en el sistema.')
        ]);

        return $layout;
    }

    public function actualizarPagina(Request $request)
    {
        $clienteSeleccionado = $request->get('cliente_id');

        return redirect()->route('platform.impresoras_contador', [
            'cliente_id' => $clienteSeleccionado
        ])->withInput();
    }
}
