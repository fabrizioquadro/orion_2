<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracao;

class ConfiguracaoController extends Controller
{
    public function index(){
        session()->put('menu_active','menu_configuracoes');

        $config = Configuracao::where('id','1')->first();
        return view('adm/configuracoes/index', compact('config'));
    }

    public function setar(Request $request){
        try {
            $config = Configuracao::where('id','1')->first();
            $config->tempo_investimento = $request->tempo_investimento;
            $config->minimo_investimento = valorFormDb($request->minimo_investimento);
            $config->ate_ciquenta = $request->ate_ciquenta;
            $config->ate_cem = $request->ate_cem;
            $config->acima_cem = $request->acima_cem;
            $config->save();

            return redirect()->route('adm.configuracoes')->with('mensagem', 'Valores Setados');
        } catch (\Exception $e) {
            return redirect()->route('adm.configuracoes')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function contrato(Request $request){
        try {
            $config = Configuracao::where('id','1')->first();
            $config->modelo_contrato = $request->modelo_contrato;
            $config->save();
            return redirect()->route('adm.configuracoes')->with('mensagem','Modelo Contrato Salvo!');
        } catch (\Exception $e) {
            return redirect()->route('adm.configuracoes')->with('mensagem',$e->getMessage());
        }

    }

}
