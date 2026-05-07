<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investimento;
use App\Models\Configuracao;
use App\Models\Investidor;

class InvestimentoInvestidorController extends Controller
{
    public function index(){
        //setar o menu
        session()->put('menu_active','menu_investimento');

        $user = auth()->user();
        $investimentos = Investimento::where('user_id', $user->id)->get();

        return view('investidor/investimentos/index', compact('investimentos'));
    }

    public function adicionar(){
        //setar o menu
        session()->put('menu_active','menu_investimento');

        return view('investidor/investimentos/adicionar');
    }

    public function insert(Request $request){
        try {
            $user = auth()->user();
            $config = Configuracao::where('id', '1')->first();
            $vl_investimento = valorFormDb($request->vl_investimento);

            if($vl_investimento < $config->minimo_investimento){
                return redirect()->route('investidor.investimentos')->with('mensagem_erro', 'Valor mínimo de investimento é de R$ '.valorDbForm($config->minimo_investimento));
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

            $investidor = Investidor::where('user_id', $user->id)->first();
            if($investidor->st_renda_total == 'Sim'){
                $vl_retorno_ganho = 0;
            }

            $dados = [
                'user_id' => $user->id,
                'titulo' => $request->titulo,
                'dt_investimento' => $request->dt_investimento,
                'vl_investimento' => $vl_investimento,
                'dt_retorno' => $dt_retorno,
                'vl_retorno_ganho' => $vl_retorno_ganho,
                'st_investimento' => 'Ativo',
                'investimento_cofirmado' => 'Não',
                'vl_cota_investido' => '0.00',
                'vl_cota_restante' => $vl_investimento,
                'indice_rendimento' => $porcentagem_ganho,
            ];

            $investimento = Investimento::create($dados);

            if($request->hasFile('comprovante') && $request->file('comprovante')->isValid()){
                $imagem = $request->comprovante;
                $extensao = $imagem->extension();

                $nm_imagem = $investimento->id.".".$extensao;
                $request->comprovante->move(public_path('comprovante_investimentos'), $nm_imagem);

                $investimento->comprovante = $nm_imagem;
                $investimento->save();
            }

            return redirect()->route('investidor.investimentos')->with('mensagem', 'Investimento Cadastrado! Pendente de confirmação!');
        } catch (\Exception $e) {
            return redirect()->route('investidor.investimentos')->with('mensagem_erro', $e->getMessage());
        }
    }

}
