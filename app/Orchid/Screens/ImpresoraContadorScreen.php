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
    //     $diferenciasPorContrato = [];
    //     $impresoras = collect();
    
    //     if ($clienteSeleccionado) {
    //         $impresoras = $this->obtenerImpresorasYReemplazosPorCliente($clienteSeleccionado);
    
    //         foreach ($impresoras as $item) {
    //             if (!isset($item['impresora'])) {
    //                 continue;
    //             }
    
    //             $impresora = $item['impresora'];
    //             $contrato = $impresora->contrato;
    
    //             if (!$contrato) {
    //                 continue;
    //             }
    
    //             // Obtener copias mínimas según tipo de contrato
    //             $copiasMinimas = $contrato->tipo_minimo === 'grupal'
    //                 ? $contrato->copias_minimas
    //                 : $this->obtenerCopiasMinimasIndividuales($contrato->id, $impresora->id);
    
    //             // Obtener contadores
    //             $contadorInicial = $item['datos_congelados']['contador_inicial'] ?? $impresora->obtenerUltimoContador() ?? 0;
    //             $contadorFinal = $item['datos_congelados']['contador_final'] ?? $impresora->obtenerContadorActual() ?? 0;
    
    //             // Diferencia actual
    //             $diferenciaActual = max(0, $contadorFinal - $contadorInicial);
    
    //             // Diferencia total (incluyendo reemplazo si aplica)
    //             $diferenciaReemplazo = $item['diferencia_total'] ?? 0;
    //             $diferenciaTotal = $diferenciaActual + $diferenciaReemplazo;
    
    //             $totalCopias += $diferenciaTotal;
    
    //             $numeroContrato = $contrato->numero_contrato;
    
    //             // Inicializar datos del contrato si no existen
    //             if (!isset($diferenciasPorContrato[$numeroContrato])) {
    //                 $diferenciasPorContrato[$numeroContrato] = $this->inicializarDatosContrato($contrato);
    //                 $diferenciasPorContrato[$numeroContrato]['impresoras'] = [];
    //             }
    
    //             // Agregar datos de la impresora al contrato
    //             $diferenciasPorContrato[$numeroContrato]['impresoras'][] = [
    //                 'id' => $impresora->id,
    //                 'serial' => $impresora->serial,
    //                 'contador_inicial' => $contadorInicial,
    //                 'contador_final' => $contadorFinal,
    //                 'diferencia' => $diferenciaTotal,
    //                 'valor_por_copia' => $contrato->valor_por_copia,
    //                 'copias_minimas' => $copiasMinimas,
    //                 'tipo_minimo' => $contrato->tipo_minimo,
    //                 'es_reemplazo' => $item['es_reemplazo'] ?? false,
    //                 'impresora_original_serial' => $item['impresora_original_serial'] ?? null,
    //             ];
    
    //             $diferenciasPorContrato[$numeroContrato]['suma_diferencias'] += $diferenciaTotal;
    
    //             if ($contrato->tipo_minimo === 'individual') {
    //                 $diferenciasPorContrato[$numeroContrato]['costo_contrato'] += $this->calcularCostoIndividual($diferenciaTotal, $copiasMinimas, $contrato->valor_por_copia);
    //             }
    //         }
    
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
    
    //     return [
    //         'impresoras' => $impresoras->map(function ($item) {
    //             if (!isset($item['impresora'])) {
    //                 return [
    //                     'serial' => '<span class="text-danger">Sin datos</span>',
    //                     'contador_inicial' => 0,
    //                     'contador_final' => 0,
    //                     'diferencia' => 0,
    //                     'valor_por_copia' => 0,
    //                     'copias_minimas' => 'N/A',
    //                     'tipo_minimo' => 'N/A',
    //                 ];
    //             }
    
    //             $impresora = $item['impresora'];
    //             $contrato = $impresora->contrato;
    
    //             return [
    //                 'serial' => $impresora->serial,
    //                 'es_reemplazo' => $item['es_reemplazo'] ?? false,
    //                 'impresora_original_serial' => $item['impresora_original_serial'] ?? null,
    //                 'contador_inicial' => $item['datos_congelados']['contador_inicial'] ?? $impresora->obtenerUltimoContador(),
    //                 'contador_final' => $item['datos_congelados']['contador_final'] ?? $impresora->obtenerContadorActual(),
    //                 'diferencia' => $item['diferencia_total'] ?? 0,
    //                 'valor_por_copia' => $contrato->valor_por_copia ?? 'N/A',
    //                 'copias_minimas' => $contrato->tipo_minimo === 'grupal'
    //                     ? $contrato->copias_minimas
    //                     : $this->obtenerCopiasMinimasIndividuales($contrato->id, $impresora->id),
    //                 'tipo_minimo' => $contrato->tipo_minimo ?? 'N/A',
    //                 'numero_contrato' => $contrato->numero_contrato ?? 'No disponible',
    //             ];
    //         }),
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
        $diferenciasPorContrato = [];
        $impresoras = collect();
    
        if ($clienteSeleccionado) {
            $impresoras = $this->obtenerImpresorasYReemplazosPorCliente($clienteSeleccionado);
            foreach ($impresoras as $item) {
                $diferenciaTotal = intval($item['diferencia_total']);
                $contadorInicial = intval($item['contador_inicial']);
                $contadorFinal = intval($item['contador_final']);
            
                $impresora = $item['impresora'];
                $contrato = $impresora->contrato;
            
                if (!$contrato) {
                    continue;
                }
            
                $numeroContrato = $contrato->numero_contrato;
            
                // Inicializar datos del contrato si no existen
                if (!isset($diferenciasPorContrato[$numeroContrato])) {
                    $diferenciasPorContrato[$numeroContrato] = $this->inicializarDatosContrato($contrato);
                    $diferenciasPorContrato[$numeroContrato]['impresoras'] = [];
                }

          
                
            
                // Determinar las copias mínimas según el tipo de contrato
                $tipoMinimo = $contrato->tipo_minimo;
                $valorPorCopia = floatval($contrato->valor_por_copia);
                $copiasMinimas = 0;


                
            
                if ($tipoMinimo === 'grupal') {
                    $copiasMinimas = intval($contrato->copias_minimas ?? 0);
                } elseif ($tipoMinimo === 'individual') {
                    $copiasMinimas = $this->obtenerCopiasMinimasIndividuales($contrato->id, $impresora->id);
                } else {
                    $copiasMinimas = 0; // Manejar un caso inesperado como valor predeterminado
                }
                
           
                
            
                // Calcular costo dependiendo del tipo de contrato
                $costoImpresora = 0;
                if ($tipoMinimo === 'directo') {
                    $costoImpresora = $diferenciaTotal * $valorPorCopia;
                } else {
                    $costoImpresora = ($diferenciaTotal < $copiasMinimas ? $copiasMinimas : $diferenciaTotal) * $valorPorCopia;
                }
            
                // Agregar datos al contrato
                $diferenciasPorContrato[$numeroContrato]['impresoras'][] = [
                    'id' => $impresora->id,
                    'serial' => $impresora->serial,
                    'contador_inicial' => $contadorInicial,
                    'contador_final' => $contadorFinal,
                    'diferencia' => $diferenciaTotal,
                    'valor_por_copia' => $valorPorCopia,
                    'copias_minimas' => $copiasMinimas, // Revisión del valor aquí
                    'tipo_minimo' => $tipoMinimo,
                    'es_reemplazo' => $item['es_reemplazo'],
                    'impresora_original_serial' => $item['impresora_original_serial'] ?? null,
                ];
            
                // Sumar diferencias y costos
                $diferenciasPorContrato[$numeroContrato]['suma_diferencias'] += $diferenciaTotal;
                $diferenciasPorContrato[$numeroContrato]['costo_contrato'] += $costoImpresora;
            
                // Acumular totales generales
                $totalCopias += $diferenciaTotal;
                $totalCosto += $costoImpresora;
            }
    
            foreach ($diferenciasPorContrato as &$datosContrato) {
                if ($datosContrato['tipo_minimo'] === 'grupal') {
                    $datosContrato['costo_contrato'] = $this->calcularCostoGrupal(
                        $datosContrato['numero_contrato'],
                        $clienteSeleccionado,
                        $datosContrato
                    );
                }
            }
    
          //  Verificar datos finales
            // dd([
            //     'diferenciasPorContrato' => $diferenciasPorContrato,
            //     'totalCopias' => $totalCopias,
            //     'totalCosto' => $totalCosto,
            // ]);



            // dd([
            //     'contrato' => $contrato->numero_contrato,
            //     'tipo_minimo' => $tipoMinimo,
            //     'copias_minimas' => $copiasMinimas,
            //     'diferencia_total' => $diferenciaTotal,
            //     'costo_impresora' => $costoImpresora,
            // ]);
//            dd($diferenciasPorContrato[$numeroContrato]['impresoras']);




        }

            return [
                'impresoras' => $impresoras->map(function ($item) {
                    if (!isset($item['impresora'])) {
                        return [
                            'serial' => '<span class="text-danger">Sin datos</span>',
                            'contador_inicial' => 0,
                            'contador_final' => 0,
                            'diferencia' => 0,
                            'valor_por_copia' => 0,
                            'copias_minimas' => 'N/A',
                            'tipo_minimo' => 'N/A',
                        ];
                    }
            
                    $impresora = $item['impresora'];
                    $contrato = $impresora->contrato;
            
                    // Determinar las copias mínimas según el tipo de contrato
                    $copiasMinimas = 'N/A';
                    if ($contrato) {
                        if ($contrato->tipo_minimo === 'grupal') {
                            $copiasMinimas = intval($contrato->copias_minimas ?? 0);
                        } elseif ($contrato->tipo_minimo === 'individual') {
                            $copiasMinimas = $this->obtenerCopiasMinimasIndividuales($contrato->id, $impresora->id);
                        }
                    }
            
                    return [
                        'serial' => $impresora->serial,
                        'es_reemplazo' => $item['es_reemplazo'] ?? false,
                        'impresora_original_serial' => $item['impresora_original_serial'] ?? null,
                        'contador_inicial' => $item['contador_inicial'],
                        'contador_final' => $item['contador_final'],
                        'diferencia' => $item['diferencia_total'],
                        'valor_por_copia' => $contrato->valor_por_copia ?? 'N/A',
                        'copias_minimas' => $copiasMinimas,
                        'tipo_minimo' => $contrato->tipo_minimo ?? 'N/A',
                        'numero_contrato' => $contrato->numero_contrato ?? 'No disponible',
                    ];
                }),
                'clienteSeleccionado' => $clienteSeleccionado,
                'totalCosto' => $totalCosto,
                'totalCopias' => $totalCopias,
                'diferenciasPorContrato' => $diferenciasPorContrato,
            ];
        }
            
    
    private function inicializarDatosContrato($contrato)
{
    return [
        'numero_contrato' => $contrato->numero_contrato,
        'tipo_minimo' => $contrato->tipo_minimo, // Puede ser 'directo', 'grupal' o 'individual'
        'valor_por_copia' => floatval($contrato->valor_por_copia),
        'copias_minimas' => $contrato->copias_minimas ?? 0,
        'suma_diferencias' => 0,
        'costo_contrato' => 0,
    ];
}


private function obtenerCopiasMinimasIndividuales($contratoId, $impresoraId)
{
    $contratoImpresora = ContratoImpresora::where('contrato_id', $contratoId)
        ->where('impresora_id', $impresoraId)
        ->first();
    return $contratoImpresora ? intval($contratoImpresora->copias_minimas) : 0;
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
        return $reemplazo ? $reemplazo->contador_final : $impresora->contador_actual;

    }
    private function obtenerImpresorasYReemplazosPorCliente($clienteId)
    {
        $impresoras = Impresora::with('contrato')
            ->whereHas('contrato', function ($query) use ($clienteId) {
                $query->where('cliente_id', $clienteId);
            })
            ->get();
    
        $idsImpresoras = $impresoras->pluck('id');
    
        $reemplazos = Reemplazo::whereIn('id_impresora_reemplazo', $idsImpresoras)
            ->orWhereIn('id_impresora_original', $idsImpresoras)
            ->get();
    
        $impresorasConReemplazos = collect();
    
        foreach ($impresoras as $impresora) {
            $esReemplazo = false;
            $contadorFinal = intval($impresora->contador_actual ?? 0);
            $contadorInicial = 0;
    
            $reemplazoAsociado = $reemplazos->where('id_impresora_reemplazo', $impresora->id)->last();
    
            if ($reemplazoAsociado) {
                $contadorInicial = intval($reemplazoAsociado->contador_final ?? 0);
            } else {
                $contadorInicial = intval($impresora->obtenerUltimoContador() ?? 0);
            }
    
            // Garantizar que el cálculo no sea negativo
            if ($contadorFinal < $contadorInicial) {
                $temp = $contadorFinal;
                $contadorFinal = $contadorInicial;
                $contadorInicial = $temp;
            }
    
            // Calcular diferencia
            $diferenciaTotal = $contadorFinal - $contadorInicial;
    
            $impresorasConReemplazos->push([
                'impresora' => $impresora,
                'es_reemplazo' => $esReemplazo,
                'contador_inicial' => $contadorInicial,
                'contador_final' => $contadorFinal,
                'diferencia_total' => $diferenciaTotal,
                'contrato' => $impresora->contrato,
            ]);
        }
    
        return $impresorasConReemplazos;
    }
    
    
    
    // private function calcularCostoIndividual(Impresora $impresora, $diferencia, $contrato)
    // {
    //     $contratoImpresora = ContratoImpresora::where('contrato_id', $contrato->id)
    //         ->where('impresora_id', $impresora->id)
    //         ->first();

    //     $copiasMinimas = $contratoImpresora ? $contratoImpresora->copias_minimas : 0;

    //     return $diferencia < $copiasMinimas
    //         ? $copiasMinimas * $contrato->valor_por_copia
    //         : $diferencia * $contrato->valor_por_copia;
    // }

    private function calcularCostoIndividual($diferencia, $copiasMinimas, $valorPorCopia)
{
    return $diferencia < $copiasMinimas
        ? $copiasMinimas * $valorPorCopia
        : $diferencia * $valorPorCopia;
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
            $impresoras = $contrato['impresoras'];
            $cumplenMinimo = 0;
    
            foreach ($impresoras as $impresora) {
                if ($impresora['diferencia'] >= $impresora['copias_minimas']) {
                    $cumplenMinimo++;
                }
            }
    
            $totalImpresoras = count($impresoras);
            $mensajeCumplimiento = $contrato['tipo_minimo'] === 'individual'
                ? "$cumplenMinimo/$totalImpresoras impresoras cumplen con el mínimo establecido."
                : null;
    
            $cumpleContrato = true;
            foreach ($impresoras as $impresora) {
                if ($impresora['diferencia'] < $impresora['copias_minimas']) {
                    $cumpleContrato = false;
                    break;
                }
            }
    
            $cumpleMensaje = $cumpleContrato
                ? 'Cumple con el mínimo de copias.'
                : 'No cumple con el mínimo de copias.';
            $cumpleColor = $cumpleContrato ? '#28a745' : '#E74C3C'; // Verde si cumple, rojo si no cumple
    
            $rows = [
                Label::make('contrato_' . $contrato['numero_contrato'])
                    ->value('Contrato: ' . $contrato['numero_contrato'] 
                            . ' - Tipo de Contrato: ' . ucfirst($contrato['tipo_minimo']))
                    ->style('font-weight: bold; font-size: 16px; color: #2C3E50; margin-top: 10px;'),
    
                Label::make('detalleContrato_' . $contrato['numero_contrato'])
                    ->value($contrato['tipo_minimo'] === 'individual' 
                            ? 'Copias Totales: ' . number_format($contrato['suma_diferencias'], 0, '', '.') 
                              . ' - Costo Total del Contrato: $' . number_format($contrato['costo_contrato'], 0, '', '.')
                            : 'Diferencia Copias Total: ' . number_format($contrato['suma_diferencias'], 0, '', '.')
                              . ' - Costo: $' . number_format($contrato['costo_contrato'], 0, '', '.'))
                    ->style('margin-top: 5px; color: #34495E;'),
    
                Label::make('estadoContrato_' . $contrato['numero_contrato'])
                    ->value($cumpleMensaje)
                    ->style('font-style: italic; color: ' . $cumpleColor . '; margin-top: 5px;'),
            ];
    
            if ($mensajeCumplimiento) {
                $rows[] = Label::make('mensajeCumplimiento_' . $contrato['numero_contrato'])
                    ->value($mensajeCumplimiento)
                    ->style('font-weight: bold; color: #34495E; margin-top: 10px;');
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
