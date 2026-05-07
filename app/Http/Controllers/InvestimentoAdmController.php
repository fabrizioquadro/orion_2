<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investimento;
use App\Models\User;
use App\Models\Configuracao;
use App\Models\Investidor;
use App\Models\Resgate;
use Illuminate\Support\Facades\DB;

class InvestimentoAdmController extends Controller
{
    public function index(){
        //setar o menu
        session()->put('menu_active','menu_investimento');

        $investimentos = Investimento::where('vl_investimento','>','0')->get();
        return view('adm/investimentos/index', compact('investimentos'));
    }

    public function adicionar(){
        //setar o menu
        session()->put('menu_active','menu_investimento');

        $users = User::where('tipo','Investidor')->orderBy('nome')->get();
        return view('adm/investimentos/adicionar', compact('users'));
    }

    public function insert(Request $request){
        DB::beginTransaction();
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

            $investidor = Investidor::where('user_id', $request->user_id)->first();
            if($investidor->st_renda_total == 'Sim'){
                $vl_retorno_ganho = 0;
            }

            $dados = [
                'user_id' => $request->user_id,
                'titulo' => $request->titulo,
                'dt_investimento' => $request->dt_investimento,
                'vl_investimento' => $vl_investimento,
                'dt_retorno' => $dt_retorno,
                'vl_retorno_ganho' => $vl_retorno_ganho,
                'st_investimento' => 'Ativo',
                'investimento_cofirmado' => 'Sim',
                'vl_cota_investido' => '0.00',
                'vl_cota_restante' => $vl_investimento,
                'indice_rendimento' => $porcentagem_ganho,
            ];

            $investimento = Investimento::create($dados);

            ApiZapSignController::create_doc($investimento);

            DB::commit();
            return redirect()->route('adm.investimentos')->with('mensagem', 'Investimento Cadastrado!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('adm.investimentos')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function editar($id){
        //setar o menu
        session()->put('menu_active','menu_investimento');

        $investimento = Investimento::where('id', $id)->first();
        $users = User::where('tipo','Investidor')->orderBy('nome')->get();
        return view('adm/investimentos/editar', compact('users','investimento'));
    }

    public function update(Request $request){
        DB::beginTransaction();
        try {
            $config = Configuracao::where('id', '1')->first();

            $invest_update = Investimento::where('id', $request->investimento_id)->first();

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
                'vl_cota_restante' => $vl_investimento - $invest_update->vl_cota_investido,
                'indice_rendimento' => $porcentagem_ganho,
            ];

            Investimento::where('id', $request->investimento_id)->update($dados);
            DB::commit();
            return redirect()->route('adm.investimentos')->with('mensagem', 'Investimento Editado!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('adm.investimentos')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function excluir($id){
        //setar o menu
        session()->put('menu_active','menu_investimento');

        $investimento = Investimento::where('id', $id)->first();
        return view('adm/investimentos/excluir', compact('investimento'));
    }

    public function delete(Request $request){
        DB::beginTransaction();
        try {
            Investimento::where('id', $request->investimento_id)->delete();
            DB::commit();
            return redirect()->route('adm.investimentos')->with('mensagem', 'Investimento Excluído!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('adm.investimentos')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function finalizar($id){
        //setar o menu
        session()->put('menu_active','menu_investimento');

        $investimento = Investimento::where('id', $id)->first();
        return view('adm/investimentos/finalizar', compact('investimento'));
    }

    public function finalizar_set(Request $request){
        $investimento = Investimento::where('id', $request->investimento_id)->first();
        $retorno = $this->finalizar_investimento($investimento);
        if($retorno){
            return redirect()->route('adm.investimentos')->with('mensagem','Investimento Finalizado');
        }
        else{
            return redirect()->route('adm.investimentos')->with('mensagem_erro','Ocorreu um erro inesperado no sistema');
        }
    }

    public function analise_situacao(){
        try {
            $data = date('Y-m-d');
            $investimentos = Investimento::where('st_investimento','Ativo')
            ->where('dt_retorno','<=',$data)
            ->get();

            $controle = true;

            foreach($investimentos as $investimento){
                $retorno = $this->finalizar_investimento($investimento);
                if(!$retorno){
                    $controle = false;
                }
            }

            if($controle){
                $mensagem = "Análise Executada Corretamente!";
            }
            else{
                $mensagem = "Análise Executada INCORRETAMENTE!";
            }

            return redirect()->route('adm.investimentos')->with('mensagem', $mensagem);
        } catch (\Exception $e) {
            return redirect()->route('adm.investimentos')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function analise_situacao_old(){
        try {
            $data = date('Y-m-d');
            Investimento::where('st_investimento','Ativo')
            ->where('dt_retorno','<=',$data)
            ->update(['st_investimento' => 'Finalizado']);

            return redirect()->route('adm.investimentos')->with('mensagem', 'Análise Executada!');
        } catch (\Exception $e) {
            return redirect()->route('adm.investimentos')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function finalizar_investimento($investimento){
        DB::beginTransaction();

        try {
            $investidor = Investidor::where('user_id', $investimento->user->id)->first();
            if($investidor->st_renda_total == 'Não'){
                if($investidor->user_id_indicador && $investidor->user_id_indicador != 3 && $investidor->user_id_indicador != 2){
                    //se entrar aqui vamos enviar a comissão
                    $dados = [
                        'user_id' => $investidor->user_id_indicador,
                        'titulo' => 'Comissão de investimento de '.$investimento->user->nome,
                        'dt_investimento' => $investimento->dt_retorno,
                        'vl_investimento' => '0.00',
                        'dt_retorno' => $investimento->dt_retorno,
                        'vl_retorno_ganho' => round($investimento->vl_retorno_ganho / 2, 2),
                        'st_investimento' => 'Finalizado',
                        'investimento_cofirmado' => 'Sim',
                        'vl_cota_investido' => '0.00',
                        'vl_cota_restante' => '0.00',
                        'retorno_comissao' => 'Sim',
                    ];

                    $invest = Investimento::create($dados);

                    $dados = [
                        'user_id' => $invest->user_id,
                        'titulo' => "Saque ".$invest->titulo,
                        'dt_resgate' => $invest->dt_retorno,
                        'vl_resgate' => $invest->vl_retorno_ganho,
                        'resgate_confirmado' => 'Sim',
                    ];
                    Resgate::create($dados);
                }
                $investimento->st_investimento = 'Finalizado';
                $investimento->save();
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }



    }

}
