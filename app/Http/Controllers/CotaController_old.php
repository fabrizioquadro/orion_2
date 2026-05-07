<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cota;
use App\Models\Investimento;
use App\Models\CotaInvestimento;
use App\Models\Investidor;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CotaController_old extends Controller
{
    public function index(){
        //setar o menu
        session()->put('menu_active','menu_cotas');

        $cotas = Cota::all();
        return view('adm/cotas/index', compact('cotas'));
    }

    public function adicionar(){
        //setar o menu
        session()->put('menu_active','menu_cotas');

        //vamos buscar os investimentos que estão disponiveis
        $investimentos = Investimento::where('vl_cota_restante','>','0.00')
        ->where('investimento_cofirmado', 'Sim')
        ->get();
        return view('adm/cotas/adicionar', compact('investimentos'));
    }

    public function insert(Request $request){
        DB::beginTransaction();
        try {
            $lucro_bruto = valorFormDb($request->vl_venda) - valorFormDb($request->vl_compra);
            $lucro_ratiado = round($lucro_bruto / 2, 2);
            $comissao = 2 * round($lucro_ratiado / 100, 2);
            $lucro_liquido = round($lucro_ratiado - $comissao, 2);

            $dados = [
                'codigo' => $request->codigo,
                'vl_carta' => valorFormDb($request->vl_carta),
                'parcelas' => $request->parcelas,
                'vl_parcela' => valorFormDb($request->vl_parcela),
                'dt_compra' => $request->dt_compra,
                'dt_venda' => $request->dt_venda,
                'dias_venda' => (strtotime($request->dt_venda) - strtotime($request->dt_compra)) / 86400,
                'vl_compra' => valorFormDb($request->vl_compra),
                'vl_venda' => valorFormDb($request->vl_venda),
                'lucro_bruto' => $lucro_bruto,
                'lucro_ratiado' => $lucro_ratiado,
                'comissao' => $comissao,
                'lucro_liquido' => $lucro_liquido,
                'situacao' => 'Aberta',
                'obs' => $request->obs,
            ];

            $cota = Cota::create($dados);

            for($i=1 ; $i<=$request->contador_investimentos ; $i++){
                $var = "investimento_id_".$i;
                $investimento_id = $request->$var;

                $var = "vl_investimento_".$i;
                $investimento_valor = $request->$var;

                if($investimento_id && $investimento_valor){
                    $investimento = Investimento::where('id', $investimento_id)->first();
                    $investimento_valor = valorFormDb($investimento_valor);

                    $dados = [
                        'cota_id' => $cota->id,
                        'investimento_id' => $investimento->id,
                        'vl_investimento' => $investimento_valor,
                    ];
                    $controle = CotaInvestimento::create($dados);
                    if($controle){
                        $investimento->vl_cota_investido += $investimento_valor;
                        $investimento->vl_cota_restante -= $investimento_valor;
                        $investimento->save();
                    }
                }
            }
            DB::commit();
            return redirect()->route('adm.cotas')->with('mensagem', 'Cota Cadastrada!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('adm.cotas')->with('mensagem_erro', $e->getMessage());
        }

    }

    public function acessar($id){
        //setar o menu
        session()->put('menu_active','menu_cotas');

        $cota = Cota::where('id', $id)->first();

        $retorno = $this->gera_rateio_cota($cota->id);

        $array_rateio = $retorno['array_rateio'];
        return view('adm/cotas/acessar', compact('cota','array_rateio'));
    }

    public function gera_rateio_cota($cota_id){
        $retorno = array();
        $cota = Cota::where('id', $cota_id)->first();

        //vamos montar um array de rateio do lucro
        $array_rateio = array();
        $controle_anderson = false;
        $valor_anderson = 0;
        $valor_arismar = 0;

        //vamos começar pelos aportadores do recursos(investimentos)
        $i = 0;
        foreach($cota->investimentos as $aporte){
            $i++;
            //vamos descobrir o lucro da cota referente ao investimento global
            $porcentagem_lucro_aporte_cota = $aporte->vl_investimento * 100 / $cota->vl_compra;
            $lucro_aporte_cota = round($porcentagem_lucro_aporte_cota * $cota->lucro_liquido / 100 ,2);
            $pagamento_cota = 0;

            if($aporte->investimento->user->investidor->st_renda_total == 'Sim'){
                $array = [
                    'investidor_id' => $aporte->investimento->user->id,
                    'investidor' => $aporte->investimento->user->nome,
                    'descricao' => 'Investimento',
                    'rateio' => $lucro_aporte_cota,
                ];
                $array_rateio[] = $array;
            }
            else{
                if($aporte->vl_investimento < $aporte->investimento->vl_investimento){
                    $porcentagem = ($aporte->vl_investimento * 100 / $aporte->investimento->vl_investimento) / 100;
                    $rateio = round($aporte->investimento->vl_retorno_ganho * $porcentagem, 2);
                }
                else{
                    $rateio = $aporte->investimento->vl_retorno_ganho;
                }

                $array = [
                    'investidor' => $aporte->investimento->user->nome,
                    'descricao' => 'Investimento',
                    'rateio' => $rateio,
                ];
                $array_rateio[] = $array;
                $pagamento_cota += $rateio;

                //vamos verificar se este investido possui indicação
                $investidor = Investidor::where('user_id', $aporte->investimento->user->id)->first();
                if($investidor->user_id_indicador){
                    $indicador = User::where('id', $investidor->user_id_indicador)->first();
                    if($indicador->id != 3){
                        $rateio_indicador = round($rateio / 2, 2);
                        $array = [
                            'investidor' => $indicador->nome,
                            'descricao' => 'Comissão Indicação',
                            'rateio' => $rateio_indicador,
                        ];
                        $array_rateio[] = $array;
                        $pagamento_cota += $rateio_indicador;
                    }

                    //vamos verificar se faz parte da rede anderson
                    $controle_indicacao_anderson = $investidor->user_id_indicador;
                    $controle_anderson = false;
                    do{
                        if($controle_indicacao_anderson == 3){
                            $controle_anderson = true;
                        }
                        else{
                            $variavel = Investidor::where('user_id', $controle_indicacao_anderson)->first();
                            $controle_indicacao_anderson = $variavel->user_id_indicador;
                        }
                    }while(!$controle_anderson && $controle_indicacao_anderson);
                }

                if($controle_anderson){
                    $rateio_cota = ($lucro_aporte_cota - $pagamento_cota) / 2;
                    $valor_anderson += $rateio_cota;
                    $valor_arismar += $rateio_cota;
                }
                else{
                    $rateio_cota = $lucro_aporte_cota - $pagamento_cota;
                    $valor_arismar += $rateio_cota;
                }
            }
        }

        if($valor_anderson > 0){
            $anderson = User::where('id','3')->first();
            $array = [
                'investidor' => $anderson->nome,
                'descricao' => 'Comissão Rede Anderson, cota '.$cota->id,
                'rateio' => $valor_anderson,
            ];
            $array_rateio[] = $array;

            $retorno['retorno_anderson'] = $valor_anderson;
        }

        if($valor_arismar > 0){
            $arismar = User::where('id','2')->first();
            $array = [
                'investidor' => $arismar->nome,
                'descricao' => 'Comissão Rede Arismar, cota '.$cota->id,
                'rateio' => $valor_arismar,
            ];
            $array_rateio[] = $array;

            $retorno['retorno_arismar'] = $valor_arismar;
        }

        $retorno['array_rateio'] = $array_rateio;

        return $retorno;
    }

    public function gera_rateio_cota_old($cota_id){
        $retorno = array();
        $cota = Cota::where('id', $cota_id)->first();

        //vamos montar um array de rateio do lucro
        $array_rateio = array();
        $lucro = $cota->lucro_liquido;
        $controle_anderson = false;

        //vamos começar pelos aportadores do recursos(investimentos)
        foreach($cota->investimentos as $aporte){
            if($aporte->vl_investimento < $aporte->investimento->vl_investimento){
                $porcentagem = ($aporte->vl_investimento * 100 / $aporte->investimento->vl_investimento) / 100;
                $rateio = round($aporte->investimento->vl_retorno_ganho * $porcentagem, 2);
            }
            else{
                $rateio = $aporte->investimento->vl_retorno_ganho;
            }
            $array = [
                'investidor' => $aporte->investimento->user->nome,
                'descricao' => 'Investimento',
                'rateio' => $rateio,
            ];
            $array_rateio[] = $array;
            $lucro -= $rateio;

            //vamos verificar se este investido possui indicação
            $investidor = Investidor::where('user_id', $aporte->investimento->user->id)->first();
            if($investidor->user_id_indicador){
                //vamos verificar se possui indicação do anderson
                $controle_indicacao_anderson = $investidor->user_id_indicador;
                do{
                    if($controle_indicacao_anderson == 3){
                        $controle_anderson = true;
                    }
                    else{
                        $variavel = Investidor::where('user_id', $controle_indicacao_anderson)->first();
                        $controle_indicacao_anderson = $variavel->user_id_indicador;
                    }
                }while(!$controle_anderson && $controle_indicacao_anderson);

                //vamos calcular os valores do rateio
                $indicador = User::where('id', $investidor->user_id_indicador)->first();
                if($indicador->id != 3){
                    $rateio_indicador = round($rateio / 2, 2);
                    $array = [
                        'investidor' => $indicador->nome,
                        'descricao' => 'Comissão Indicação',
                        'rateio' => $rateio_indicador,
                    ];
                    $array_rateio[] = $array;
                    $lucro -= $rateio_indicador;
                }
            }
        }

        if($controle_anderson){
            //se entrar aqui o lucro é dividido entre o anderson e o arismar
            $lucro_dividido = round($lucro / 2, 2);
            $anderson = User::where('id','3')->first();
            $array = [
                'investidor' => $anderson->nome,
                'descricao' => 'Comissão Rede Anderson, cota '.$cota->id,
                'rateio' => $lucro_dividido,
            ];
            $array_rateio[] = $array;

            $retorno['retorno_anderson'] = $lucro_dividido;

            $arismar = User::where('id','2')->first();
            $array = [
                'investidor' => $arismar->nome,
                'descricao' => 'Comissão Rede Arismar, cota '.$cota->id,
                'rateio' => $lucro_dividido,
            ];
            $array_rateio[] = $array;

            $retorno['retorno_arismar'] = $lucro_dividido;
        }
        else{
            $arismar = User::where('id','2')->first();
            $array = [
                'investidor' => $arismar->nome,
                'descricao' => 'Comissão Rede Arismar, cota '.$cota->id,
                'rateio' => $lucro,
            ];
            $array_rateio[] = $array;

            $retorno['retorno_anderson'] = 0.00;
            $retorno['retorno_arismar'] = $lucro;
        }

        $retorno['array_rateio'] = $array_rateio;

        return $retorno;
    }

    public function excluir($id){
        //setar o menu
        session()->put('menu_active','menu_cotas');

        $cota = Cota::where('id', $id)->first();
        return view('adm/cotas/excluir', compact('cota'));
    }

    public function delete(Request $request){
        try {
            $cota = Cota::where('id', $request->cota_id)->first();
            foreach($cota->investimentos as $aporte){
                $investimento = Investimento::where('id', $aporte->investimento_id)->first();

                $investimento->vl_cota_investido -= $aporte->vl_investimento;
                $investimento->vl_cota_restante += $aporte->vl_investimento;
                $investimento->save();
                $aporte->delete();
            }
            $cota->delete();
            return redirect()->route('adm.cotas')->with('mensagem', 'Cota Excluída!');
        } catch (\Exception $e) {
            return redirect()->route('adm.cotas')->with('mensagem_erro', $e->getMessage());
        }

    }

    public function finalizar_cota(Request $request){
        dd($request);
        try {
            $cota = Cota::where('id', $request->cota_id)->first();

            $cota->dt_venda = $request->dt_venda;
            $cota->vl_venda = valorFormDb($request->vl_venda);

            $cota->lucro_bruto = $cota->vl_venda - $cota->vl_compra;
            $cota->lucro_ratiado = round($cota->lucro_bruto / 2, 2);
            $cota->comissao = 2 * round($cota->lucro_ratiado / 100, 2);
            $cota->lucro_liquido = round($cota->lucro_ratiado - $cota->comissao, 2);
            $cota->dias_venda = (strtotime($cota->dt_venda) - strtotime($cota->dt_compra)) / 86400;
            $cota->situacao = 'Finalizada';
            $cota->save();

            $retorno = $this->gera_rateio_cota($cota->id);

            //vamos analizar se a cota tem algum investimento com investidor renda total
            foreach($cota->investimentos as $aporte){
                if($aporte->investimento->user->investidor->st_renda_total == 'Sim'){
                    //vamos descobrir qual é o valor desse investidor
                    $valor_investidor = 0;
                    foreach($retorno as $linha){
                        if(isset($linha['investidor_id']) && $linha['investidor_id'] == $aporte->investimento->user->id){
                            $valor_investidor = $linha['rateio'];
                        }
                    }
                    //vamos alterar o investimento
                    $aporte->investimento->vl_retorno_ganho = $valor_investidor;
                    $aporte->investimento->dt_retorno = $cota->dt_venda;
                    $aporte->investimento->st_investimento = 'Finalizado';
                    $aporte->investimento->save();
                }
            }

            if($retorno['retorno_anderson'] > 0){
                $dados = [
                    'user_id' => '3',
                    'titulo' => 'Comissão Rede Anderson, Cota '.$cota->id,
                    'dt_investimento' => $cota->dt_venda,
                    'vl_investimento' => '0.00',
                    'dt_retorno' => $cota->dt_venda,
                    'vl_retorno_ganho' => round($retorno['retorno_anderson'], 2),
                    'st_investimento' => 'Finalizado',
                    'investimento_cofirmado' => 'Sim',
                    'vl_cota_investido' => '0.00',
                    'vl_cota_restante' => '0.00',
                    'retorno_comissao' => 'Sim',
                ];

                Investimento::create($dados);
            }

            if($retorno['retorno_arismar'] > 0){
                $dados = [
                    'user_id' => '2',
                    'titulo' => 'Comissão Rede Arismar, Cota '.$cota->id,
                    'dt_investimento' => $cota->dt_venda,
                    'vl_investimento' => '0.00',
                    'dt_retorno' => $cota->dt_venda,
                    'vl_retorno_ganho' => round($retorno['retorno_arismar'], 2),
                    'st_investimento' => 'Finalizado',
                    'investimento_cofirmado' => 'Sim',
                    'vl_cota_investido' => '0.00',
                    'vl_cota_restante' => '0.00',
                    'retorno_comissao' => 'Sim',
                ];

                Investimento::create($dados);
            }
            return redirect()->route('adm.cotas.acessar', $cota->id)->with('mensagem','Cota Finalizada');
        } catch (\Exception $e) {
            return redirect()->route('adm.cotas.acessar', $cota->id)->with('mensagem_erro',$e->getMessage());
        }
    }

    public function editar($id){
        //setar o menu
        session()->put('menu_active','menu_cotas');

        $cota = Cota::where('id', $id)->first();

        //vamos buscar os investimentos que estão disponiveis
        $investimentos = Investimento::where('vl_cota_restante','>','0.00')
        ->where('investimento_cofirmado', 'Sim')
        ->get();

        return view('adm/cotas/editar', compact('cota','investimentos'));
    }

    public function update(Request $request){
        try {
            $lucro_bruto = valorFormDb($request->vl_venda) - valorFormDb($request->vl_compra);
            $lucro_ratiado = round($lucro_bruto / 2, 2);
            $comissao = 2 * round($lucro_ratiado / 100, 2);
            $lucro_liquido = round($lucro_ratiado - $comissao, 2);

            $dados = [
                'codigo' => $request->codigo,
                'vl_carta' => valorFormDb($request->vl_carta),
                'parcelas' => $request->parcelas,
                'vl_parcela' => valorFormDb($request->vl_parcela),
                'dt_compra' => $request->dt_compra,
                'dt_venda' => $request->dt_venda,
                'dias_venda' => (strtotime($request->dt_venda) - strtotime($request->dt_compra)) / 86400,
                'vl_compra' => valorFormDb($request->vl_compra),
                'vl_venda' => valorFormDb($request->vl_venda),
                'lucro_bruto' => $lucro_bruto,
                'lucro_ratiado' => $lucro_ratiado,
                'comissao' => $comissao,
                'lucro_liquido' => $lucro_liquido,
                'situacao' => 'Aberta',
                'obs' => $request->obs,
            ];

            Cota::where('id', $request->cota_id)->update($dados);
            $cota = Cota::where('id', $request->cota_id)->first();

            //vamos verificar se há alguma cota a excluir
            if($request->investimentos_excluir){
                $excluir_ids = substr($request->investimentos_excluir,1);
                $excluir_ids = explode(',', $excluir_ids);
                foreach($excluir_ids as $investimento_id){
                    CotaInvestimento::where('id', $investimento_id)->delete();
                }
            }

            for($i=1 ; $i<=$request->contador_investimentos ; $i++){
                $var = "investimento_id_".$i;
                $investimento_id = $request->$var;

                $var = "vl_investimento_".$i;
                $investimento_valor = $request->$var;

                if($investimento_id && $investimento_valor){
                    $investimento = Investimento::where('id', $investimento_id)->first();
                    $investimento_valor = valorFormDb($investimento_valor);

                    $dados = [
                        'cota_id' => $cota->id,
                        'investimento_id' => $investimento->id,
                        'vl_investimento' => $investimento_valor,
                    ];
                    $controle = CotaInvestimento::create($dados);
                    if($controle){
                        $investimento->vl_cota_investido += $investimento_valor;
                        $investimento->vl_cota_restante -= $investimento_valor;
                        $investimento->save();
                    }
                }
            }
            DB::commit();

            return redirect()->route('adm.cotas')->with('mensagem', 'Cota Cadastrada!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('adm.cotas')->with('mensagem_erro', $e->getMessage());
        }

    }

}
