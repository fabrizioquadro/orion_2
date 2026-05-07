<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Investimento;
use App\Models\Configuracao;
use App\Models\Resgate;
use Illuminate\Support\Facades\DB;

class TransacaoAdmController extends Controller
{
    public function index(){
        //setar o menu
        session()->put('menu_active','menu_transacao');

        $users = User::whereIn('tipo', ['Investidor','Promotor'])->orderBy('nome')->get();
        return view('adm/transacoes/index', compact('users'));
    }

    public function gerar(Request $request){
        try {
            //setar o menu
            session()->put('menu_active','menu_transacao');

            $array_transacoes = array();
            $valor_disponivel = 0;
            $total_aportes = 0;
            $total_resgates = 0;

            //vamos buscar todos os investimentos desse investidor
            $user = User::where('id', $request->user_id)->first();
            $investimentos = Investimento::where('user_id', $user->id)->where('investimento_cofirmado','Sim')->get();

            foreach($investimentos as $investimento){
                $array = array();
                $array[] = strtotime($investimento->dt_investimento);
                $array[] = dataDbForm($investimento->dt_investimento);
                $array[] = $investimento->titulo;
                $array[] = $investimento->reinvestimento == 'Sim' ? 'Reinvestimento' : 'Entrada';
                $array[] = $investimento->vl_investimento;
                $array[] = $investimento->st_investimento;
                $array[] = 'Investimento';
                $array[] = '';

                //para não aparecer o valor zerado das comissões
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
                    $array[] = 'Investimento';
                    $array[] = '';

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
                $array[] = 'Resgate';
                $array[] = $resgate->id;

                $array_transacoes[] = $array;
            }

            usort($array_transacoes, function ($item1, $item2) {
                return $item1[0] <=> $item2[0]; // Operador de nave espacial (PHP 7+)
            });

            $saldo = 0;

            return view('adm/transacoes/gerar', compact('array_transacoes','user','valor_disponivel',
            'total_aportes','total_resgates','saldo'));
        } catch (\Exception $e) {
            return redirect()->route('adm.transacoes')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function reinvestir(Request $request){
        try {
            $config = Configuracao::where('id', '1')->first();
            $vl_investimento = valorFormDb($request->vl_investimento);

            if($request->indice_rendimento){
                if(strpos($request->indice_rendimento, ',')){
                    $porcentagem_ganho = sub_replace(',','.',$request->indice_rendimento);
                }
                else{
                    $porcentagem_ganho = $request->indice_rendimento;
                }
            }
            else{

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
            }

            $vl_retorno_ganho = round($vl_investimento * $porcentagem_ganho / 100, 2);
            $dt_retorno = date('Y-m-d', strtotime("+$config->tempo_investimento days", strtotime($request->dt_investimento)));

            $dados = [
                'user_id' => $request->user_id,
                'titulo' => $request->titulo,
                'dt_investimento' => $request->dt_investimento,
                'vl_investimento' => $vl_investimento,
                'dt_retorno' => $dt_retorno,
                'vl_retorno_ganho' => $vl_retorno_ganho,
                'st_investimento' => 'Ativo',
                'reinvestimento' => 'Sim',
                'investimento_cofirmado' => 'Sim',
                'vl_cota_investido' => '0.00',
                'vl_cota_restante' => $vl_investimento,
                'indice_rendimento' => $porcentagem_ganho,
            ];

            Investimento::create($dados);

            return $this->gerar($request);
        } catch (\Exception $e) {
            return redirect()->route('adm.transacoes')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function resgatar(Request $request){
        try {
            $dados = [
                'user_id' => $request->user_id,
                'titulo' => $request->descricao,
                'dt_resgate' => $request->dt_resgate,
                'vl_resgate' => valorFormDb($request->vl_resgate),
                'resgate_confirmado' => 'Sim',
            ];

            Resgate::create($dados);

            return $this->gerar($request);
        } catch (\Exception $e) {
            return redirect()->route('adm.transacoes')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function get_resgate(){
        $resgate = Resgate::where('id', $_GET['id'])->first();
        $retorno['resgate_id'] = $resgate->id;
        $retorno['dt_resgate'] = $resgate->dt_resgate;
        $retorno['vl_resgate'] = valorDbForm($resgate->vl_resgate);
        $retorno['descricao'] = $resgate->titulo;

        echo json_encode($retorno);
    }

    public function resgatar_update(Request $request){
        DB::beginTransaction();
        try {
            $dados = [
                'titulo' => $request->descricao,
                'dt_resgate' => $request->dt_resgate,
                'vl_resgate' => valorFormDb($request->vl_resgate),
                'resgate_confirmado' => 'Sim',
            ];

            Resgate::where('id', $request->resgate_id)->update($dados);
            $resgate = Resgate::where('id', $request->resgate_id)->first();
            $request->user_id = $resgate->user_id;
            DB::commit();
            return $this->gerar($request);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('adm.transacoes')->with('mensagem_erro', $e->getMessage());
        }

    }

    public function resgatar_delete(Request $request){
        DB::beginTransaction();
        try {
            $resgate = Resgate::where('id', $request->resgate_id)->first();
            $request->user_id = $resgate->user_id;

            $resgate->delete();
            DB::commit();
            return $this->gerar($request);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('adm.transacoes')->with('mensagem_erro', $e->getMessage());
        }

    }
}
