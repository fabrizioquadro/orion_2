<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investimento;
use App\Models\Cota;
use App\Models\CotaInvestimento;
use App\Models\Investidor;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardInvestidorV2Controller extends Controller
{
    public function index()
    {
        // Setar o menu
        session()->put('menu_active', 'menu_dashboard');

        $user = auth()->user();
        $investidor = Investidor::where('user_id', $user->id)->first();

        // Buscar todos os aportes do investidor em cotas
        $meus_aportes = CotaInvestimento::whereHas('investimento', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('investimento_cofirmado', 'Sim');
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

        $patrimonio_total = $total_capital_investido + $total_lucro;
        $porcentagem_lucro_total = $total_capital_investido > 0 ? round(($total_lucro * 100) / $total_capital_investido, 2) : 0;
        
        $proxima_cota = collect($dados_cotas)->where('situacao', 'Aberta')->sortBy('dt_venda')->first();
        
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
        // Esta é uma implementação simplificada para gerar o gráfico. 
        // Em um sistema real, poderíamos ter uma tabela de histórico de patrimônio.
        $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $labels = [];
        $valores = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $mes_ref = date('Y-m', strtotime("-$i months"));
            $labels[] = $meses[intval(date('m', strtotime($mes_ref))) - 1];
            
            // Soma o capital investido até aquele mês (simplificado)
            $soma = Investimento::where('user_id', $user_id)
                ->where('investimento_cofirmado', 'Sim')
                ->where('dt_investimento', '<=', date('Y-m-t', strtotime($mes_ref)))
                ->sum('vl_investimento');
            
            $valores[] = $soma;
        }

        return [
            'labels' => $labels,
            'valores' => $valores
        ];
    }
}
