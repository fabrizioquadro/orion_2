<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investimento;
use App\Models\Resgate;
use App\Models\Configuracao;

class TransacaoInvestidorController extends Controller
{
    public function index(){
        try {
            //setar o menu
            session()->put('menu_active','menu_transacao');

            $array_transacoes = array();
            $valor_disponivel = 0;
            $total_aportes = 0;
            $total_resgates = 0;

            //vamos buscar todos os investimentos desse investidor
            $user = auth()->user();
            $investimentos = Investimento::where('user_id', $user->id)->where('investimento_cofirmado','Sim')->get();

            foreach($investimentos as $investimento){
                $array = array();
                $array[] = strtotime($investimento->dt_investimento);
                $array[] = dataDbForm($investimento->dt_investimento);
                $array[] = $investimento->titulo;
                $array[] = $investimento->reinvestimento == 'Sim' ? 'Reinvestimento' : 'Entrada';
                $array[] = $investimento->vl_investimento;
                $array[] = $investimento->st_investimento;

                if($investimento->vl_investimento > 0){
                    $array_transacoes[] = $array;
                }

                if($investimento->reinvestimento == 'Sim'){
                    $valor_disponivel -= $investimento->vl_investimento;
                }
                else{
                    $total_aportes += $investimento->vl_investimento;
                }

                if($investimento->st_investimento == 'Finalizado'){
                    $array = array();
                    $array[] = strtotime($investimento->dt_retorno);
                    $array[] = dataDbForm($investimento->dt_retorno);
                    $array[] = 'Retorno investimento: '.$investimento->titulo;
                    $array[] = 'Entrada';
                    $array[] = $investimento->vl_retorno_ganho;
                    $array[] = $investimento->st_investimento;

                    $array_transacoes[] = $array;

                    $valor_disponivel += $investimento->vl_investimento + $investimento->vl_retorno_ganho;
                }
            }

            $resgates = Resgate::where('user_id', $user->id)->where('resgate_confirmado','Sim')->get();

            foreach($resgates as $resgate){
                $total_resgates += $resgate->vl_resgate;
                $valor_disponivel -= $resgate->vl_resgate;

                $array = array();
                $array[] = strtotime($resgate->dt_resgate);
                $array[] = dataDbForm($resgate->dt_resgate);
                $array[] = $resgate->titulo;
                $array[] = 'Saída';
                $array[] = $resgate->vl_resgate;
                $array[] = 'Finalizado';

                $array_transacoes[] = $array;
            }

            usort($array_transacoes, function ($item1, $item2) {
                return $item1[0] <=> $item2[0]; // Operador de nave espacial (PHP 7+)
            });

            $saldo = 0;

            return view('investidor/transacoes/index', compact('array_transacoes','user','valor_disponivel',
            'total_aportes','total_resgates','saldo'));
        } catch (\Exception $e) {
            return redirect()->route('adm.transacoes')->with('mensagem_erro', $e->getMessage());
        }

    }

    public function reinvestir(Request $request){
        try {
            $config = Configuracao::where('id', '1')->first();
            $vl_investimento = valorFormDb($request->vl_investimento);

            if($vl_investimento < $config->minimo_investimento){
                return redirect()->route('adm.investimentos')->with('mensagem_erro', 'Valor mínimo de investimento é de R$ '.valorDbForm($config->minimo_investimento));
                die();
            }
            if($vl_investimento <= 50000){
                $porcentagem_ganho = $config->ate_ciquenta;
            }
            elseif($vl_investimento <= 100000){
                $porcentagem_ganho = $config->ate_cem;
            }
            else{
                $porcentagem_ganho = $config->acima_cem;
            }

            $vl_retorno_ganho = round($vl_investimento * $porcentagem_ganho / 100, 2);
            $dt_retorno = date('Y-m-d', strtotime("+$config->tempo_investimento days", strtotime($request->dt_investimento)));

            $user = auth()->user();

            $dados = [
                'user_id' => $user->id,
                'titulo' => $request->titulo,
                'dt_investimento' => $request->dt_investimento,
                'vl_investimento' => $vl_investimento,
                'dt_retorno' => $dt_retorno,
                'vl_retorno_ganho' => $vl_retorno_ganho,
                'st_investimento' => 'Ativo',
                'reinvestimento' => 'Sim',
                'investimento_cofirmado' => 'Não',
                'vl_cota_investido' => '0.00',
                'vl_cota_restante' => $vl_investimento,
                'indice_rendimento' => $porcentagem_ganho,
            ];

            Investimento::create($dados);
            return redirect()->route('investidor.transacoes')->with('mensagem', 'Reinvestimento Cadastrado. Pendente Confirmação!');
        } catch (\Exception $e) {
            return redirect()->route('investidor.transacoes')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function resgatar(Request $request){
        try {
            $dados = [
                'user_id' => auth()->user()->id,
                'titulo' => $request->descricao,
                'dt_resgate' => $request->dt_resgate,
                'vl_resgate' => valorFormDb($request->vl_resgate),
                'resgate_confirmado' => 'Não',
            ];

            Resgate::create($dados);

            return redirect()->route('investidor.transacoes')->with('mensagem', 'Resgate Cadastrado. Pendente Confirmação!');
        } catch (\Exception $e) {
            return redirect()->route('investidor.transacoes')->with('mensagem_erro', $e->getMessage());
        }
    }

}
