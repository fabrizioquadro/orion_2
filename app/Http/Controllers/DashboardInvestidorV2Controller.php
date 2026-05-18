<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investimento;
use App\Models\Cota;
use App\Models\CotaInvestimento;
use App\Models\Investidor;
use App\Models\User;
use App\Models\Resgate;
use Illuminate\Support\Facades\DB;

class DashboardInvestidorV2Controller extends Controller
{
    public function index()
    {
        // Setar o menu
        session()->put('menu_active', 'menu_dashboard');

        $user = auth()->user();
        $investidor = Investidor::where('user_id', $user->id)->first();

        // Buscar todos os aportes do investidor em cotas que estejam abertas
        $meus_aportes = CotaInvestimento::whereHas('investimento', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('investimento_cofirmado', 'Sim');
        })->whereHas('cota', function ($query) {
            $query->where('situacao', 'Aberta');
        })->with(['cota', 'investimento'])->get();

        $dados_cotas = [];
        $total_capital_investido = 0;
        $total_lucro = 0;
        $prazos_restantes = [];
        $rentabilidades = [];

        foreach ($meus_aportes as $aporte) {
            $cota = $aporte->cota;
            if (!$cota) continue;

            // Lógica de rateio adaptada do CotaController para este aporte específico
            $porcentagem_lucro_aporte_cota = $cota->vl_compra > 0 ? ($aporte->vl_investimento * 100 / $cota->vl_compra) : 0;
            $lucro_aporte_cota = round($porcentagem_lucro_aporte_cota * $cota->lucro_liquido / 100, 2);
            
            $meu_lucro = 0;
            $pagamento_cota_para_outros = 0;

            if ($investidor->st_renda_total == 'Sim') {
                $meu_lucro = $lucro_aporte_cota;
            } else {
                // Cálculo do rateio baseado no ganho do investimento
                if ($aporte->investimento->vl_investimento > 0 && $aporte->vl_investimento < $aporte->investimento->vl_investimento) {
                    $porcentagem = ($aporte->vl_investimento * 100 / $aporte->investimento->vl_investimento) / 100;
                    $meu_lucro = round($aporte->investimento->vl_retorno_ganho * $porcentagem, 2);
                } else {
                    $meu_lucro = $aporte->investimento->vl_retorno_ganho;
                }
            }

            $total_capital_investido += $aporte->vl_investimento;
            $total_lucro += $meu_lucro;

            // Cálculos para o card da cota
            $dt_compra = strtotime($cota->dt_compra);
            $dt_venda = strtotime($cota->dt_venda);
            $hoje = strtotime(date('Y-m-d'));
            
            $dias_totais = ($dt_venda - $dt_compra) / 86400;
            if ($dias_totais <= 0) $dias_totais = 1;
            
            $dias_passados = ($hoje - $dt_compra) / 86400;
            $dias_restantes = ($dt_venda - $hoje) / 86400;
            
            $porcentagem_prazo = round(($dias_passados * 100) / $dias_totais);
            if ($porcentagem_prazo > 100) $porcentagem_prazo = 100;
            if ($porcentagem_prazo < 0) $porcentagem_prazo = 0;

            $prazos_restantes[] = max(0, $dias_restantes);
            if ($aporte->vl_investimento > 0) {
                $rentabilidades[] = ($meu_lucro * 100) / $aporte->vl_investimento;
            }

            $dados_cotas[] = [
                'id' => $cota->id,
                'codigo' => $cota->codigo,
                'valor_investido' => $aporte->vl_investimento,
                'lucro' => $meu_lucro,
                'dias_restantes' => max(0, round($dias_restantes)),
                'porcentagem_prazo' => $porcentagem_prazo,
                'situacao' => $cota->situacao,
                'dt_venda' => $cota->dt_venda
            ];
        }

        $patrimonio_total = $this->getSaldoTransacoes($user->id);
        $total_lucro = Investimento::where('user_id', $user->id)
            ->where('investimento_cofirmado', 'Sim')
            ->sum('vl_retorno_ganho');
        $total_capital_investido = Investimento::where('user_id', $user->id)
            ->where('investimento_cofirmado', 'Sim')
            ->where(function ($query) {
                $query->where('reinvestimento', '!=', 'Sim')
                      ->orWhereNull('reinvestimento');
            })
            ->sum('vl_investimento');
        $porcentagem_lucro_total = $total_capital_investido > 0 ? round(($total_lucro * 100) / $total_capital_investido, 2) : 0;
        
        $invest_proximo = Investimento::where('user_id', $user->id)
            ->where('investimento_cofirmado', 'Sim')
            ->where('st_investimento', '!=', 'Finalizado')
            ->orderBy('dt_retorno')
            ->first();

        $proxima_cota = null;
        if ($invest_proximo) {
            $hoje = strtotime(date('Y-m-d'));
            $dt_retorno = strtotime($invest_proximo->dt_retorno);
            $dias_restantes = ($dt_retorno - $hoje) / 86400;

            $proxima_cota = [
                'titulo' => $invest_proximo->titulo,
                'codigo' => $invest_proximo->titulo,
                'dias_restantes' => max(0, round($dias_restantes)),
                'dt_retorno' => dataDbForm($invest_proximo->dt_retorno)
            ];
        }
        
        $rentabilidade_media = count($rentabilidades) > 0 ? round(array_sum($rentabilidades) / count($rentabilidades), 2) : 0;
        $prazo_medio_restante = count($prazos_restantes) > 0 ? round(array_sum($prazos_restantes) / count($prazos_restantes)) : 0;

        // Dados para o gráfico (Evolução do Patrimônio) - Mockup simplificado baseado em datas de investimento
        $grafico_patrimonio = $this->getDadosGrafico($user->id);

        return view('investidor/dashboard_v2/index', compact(
            'patrimonio_total',
            'total_lucro',
            'porcentagem_lucro_total',
            'total_capital_investido',
            'meus_aportes',
            'proxima_cota',
            'dados_cotas',
            'rentabilidade_media',
            'prazo_medio_restante',
            'grafico_patrimonio'
        ));
    }

    private function getDadosGrafico($user_id)
    {
        $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $labels = [];
        $valores = [];

        // 1. Obter todas as transações do investidor de forma similar ao getSaldoTransacoes
        $array_transacoes = [];

        // Buscar todos os investimentos confirmados do investidor
        $investimentos = Investimento::where('user_id', $user_id)
            ->where('investimento_cofirmado', 'Sim')
            ->get();

        foreach ($investimentos as $investimento) {
            if ($investimento->vl_investimento > 0) {
                $array_transacoes[] = [
                    'date' => $investimento->dt_investimento,
                    'timestamp' => strtotime($investimento->dt_investimento),
                    'tipo' => $investimento->reinvestimento == 'Sim' ? 'Reinvestimento' : 'Entrada',
                    'valor' => $investimento->vl_investimento
                ];
            }

            if ($investimento->st_investimento == 'Finalizado') {
                $array_transacoes[] = [
                    'date' => $investimento->dt_retorno,
                    'timestamp' => strtotime($investimento->dt_retorno),
                    'tipo' => 'Entrada',
                    'valor' => $investimento->vl_retorno_ganho
                ];
            }
        }

        // Buscar resgates confirmados
        $resgates = Resgate::where('user_id', $user_id)
            ->where('resgate_confirmado', 'Sim')
            ->get();

        foreach ($resgates as $resgate) {
            $array_transacoes[] = [
                'date' => $resgate->dt_resgate,
                'timestamp' => strtotime($resgate->dt_resgate),
                'tipo' => 'Saída',
                'valor' => $resgate->vl_resgate
            ];
        }

        // Ordenar transações por data (timestamp)
        usort($array_transacoes, function ($item1, $item2) {
            return $item1['timestamp'] <=> $item2['timestamp'];
        });

        // 2. Calcular o saldo acumulado ao final de cada um dos últimos 12 meses (último ano)
        for ($i = 11; $i >= 0; $i--) {
            $mes_ref = date('Y-m', strtotime("-$i months"));
            $labels[] = $meses[intval(date('m', strtotime($mes_ref))) - 1];
            
            $limite_data = date('Y-m-t', strtotime($mes_ref)); // Último dia do mês

            $saldo = 0;
            foreach ($array_transacoes as $linha) {
                // Apenas processa se a transação ocorreu até o limite de data do mês de referência
                if ($linha['date'] <= $limite_data) {
                    if ($linha['tipo'] == 'Entrada') {
                        $saldo += $linha['valor'];
                    } elseif ($linha['tipo'] == 'Saída') {
                        $saldo -= $linha['valor'];
                    }
                }
            }
            
            $valores[] = $saldo;
        }

        return [
            'labels' => $labels,
            'valores' => $valores
        ];
    }

    private function getSaldoTransacoes($user_id)
    {
        $array_transacoes = [];

        // Buscar todos os investimentos confirmados do investidor
        $investimentos = Investimento::where('user_id', $user_id)
            ->where('investimento_cofirmado', 'Sim')
            ->get();

        foreach ($investimentos as $investimento) {
            if ($investimento->vl_investimento > 0) {
                $array_transacoes[] = [
                    'timestamp' => strtotime($investimento->dt_investimento),
                    'tipo' => $investimento->reinvestimento == 'Sim' ? 'Reinvestimento' : 'Entrada',
                    'valor' => $investimento->vl_investimento
                ];
            }

            if ($investimento->st_investimento == 'Finalizado') {
                $array_transacoes[] = [
                    'timestamp' => strtotime($investimento->dt_retorno),
                    'tipo' => 'Entrada',
                    'valor' => $investimento->vl_retorno_ganho
                ];
            }
        }

        // Buscar resgates confirmados
        $resgates = Resgate::where('user_id', $user_id)
            ->where('resgate_confirmado', 'Sim')
            ->get();

        foreach ($resgates as $resgate) {
            $array_transacoes[] = [
                'timestamp' => strtotime($resgate->dt_resgate),
                'tipo' => 'Saída',
                'valor' => $resgate->vl_resgate
            ];
        }

        // Ordenar transações por data (timestamp)
        usort($array_transacoes, function ($item1, $item2) {
            return $item1['timestamp'] <=> $item2['timestamp'];
        });

        // Calcular o saldo final
        $saldo = 0;
        foreach ($array_transacoes as $linha) {
            if ($linha['tipo'] == 'Entrada') {
                $saldo += $linha['valor'];
            } elseif ($linha['tipo'] == 'Saída') {
                $saldo -= $linha['valor'];
            }
        }

        return $saldo;
    }
}
