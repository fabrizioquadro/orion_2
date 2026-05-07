<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Investimento;
use App\Models\Resgate;
use App\Models\Cota;

class RelatorioController extends Controller
{
    public function index_comissoes(){
        //setar o menu
        session()->put('menu_active','menu_comissoes');

        $investidores = User::where('tipo', 'Investidor')->orderBy('nome')->get();
        return view('adm/relatorios/index_comissoes', compact('investidores'));
    }

    public function gerar_comissoes(Request $request){
        $dados_pesquisa = $request->only('user_id','dt_inc','dt_fn');
        $comissoes = Investimento::get_comissoes_relatorio($dados_pesquisa);

        $user = null;
        if($dados_pesquisa['user_id']){
            $user = User::where('id', $dados_pesquisa['user_id'])->first();
        }
        $somatorio = 0;
        return view('adm/relatorios/gerar_comissoes', compact('user','dados_pesquisa','comissoes','somatorio'));
    }

    public function index_saques(){
        //setar o menu
        session()->put('menu_active','menu_saques');

        $investidores = User::where('tipo', 'Investidor')->orderBy('nome')->get();
        return view('adm/relatorios/index_saques', compact('investidores'));
    }

    public function gerar_saques(Request $request){
        $dados_pesquisa = $request->only('user_id','dt_inc','dt_fn');
        $saques = Resgate::get_saques_relatorio($dados_pesquisa);

        $user = null;
        if($dados_pesquisa['user_id']){
            $user = User::where('id', $dados_pesquisa['user_id'])->first();
        }
        $somatorio = 0;
        return view('adm/relatorios/gerar_saques', compact('user','dados_pesquisa','saques','somatorio'));
    }

    public function index_ganhos(){
        //setar o menu
        session()->put('menu_active','menu_ganhos');

        $investidores = User::where('tipo', 'Investidor')->orderBy('nome')->get();
        return view('adm/relatorios/index_ganhos', compact('investidores'));
    }

    public function gerar_ganhos(Request $request){
        $dados_pesquisa = $request->only('user_id','dt_inc','dt_fn','st_investimento');
        $ganhos = Investimento::get_ganhos_relatorio($dados_pesquisa);

        $user = null;
        if($dados_pesquisa['user_id']){
            $user = User::where('id', $dados_pesquisa['user_id'])->first();
        }
        $somatorio = 0;
        return view('adm/relatorios/gerar_ganhos', compact('user','dados_pesquisa','ganhos','somatorio'));
    }

    public function index_cotas(){
        //setar o menu
        session()->put('menu_active','menu_rel_cotas');

        return view('adm/relatorios/index_cotas');
    }

    public function gerar_cotas(Request $request){
        $dados_pesquisa = $request->only('dt_compra_inc','dt_compra_fn','dt_venda_inc','dt_venda_fn','situacao');
        $cotas = Cota::get_cotas_relatorio($dados_pesquisa);

        $somatorio = 0;
        return view('adm/relatorios/gerar_cotas', compact('dados_pesquisa','cotas','somatorio'));
    }

    public function index_investimentos(){
        //setar o menu
        session()->put('menu_active','menu_rel_investimentos');
        $investidores = User::where('tipo', 'Investidor')->orderBy('nome')->get();
        return view('adm/relatorios/index_investimentos', compact('investidores'));
    }

    public function gerar_investimentos(Request $request){
        $dados_pesquisa = $request->only('user_id','st_investimento','reinvestimento','investimento_confirmado','dt_investimento_inc','dt_investimento_fn','dt_retorno_inc','dt_retorno_fn');
        $investimentos = Investimento::get_investimentos_relatorio($dados_pesquisa);
        $somatorio = 0;
        $user = null;
        if($dados_pesquisa['user_id']){
            $user = User::where('id', $dados_pesquisa['user_id'])->first();
        }
        return view('adm/relatorios/gerar_investimentos', compact('dados_pesquisa','investimentos','somatorio','user'));
    }
}
