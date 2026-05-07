<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investimento extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'dt_investimento',
        'vl_investimento',
        'dt_retorno',
        'vl_retorno_ganho',
        'st_investimento',
        'reinvestimento',
        'investimento_cofirmado',
        'vl_cota_investido',
        'vl_cota_restante',
        'retorno_comissao',
        'comprovante',
        'indice_rendimento',
        'token_doc_zap_sign',
        'assinatura_contrato_investimento',
        'cota_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function get_comissoes_relatorio($dados_pesquisa){
        $query = "SELECT * FROM investimentos WHERE retorno_comissao='Sim'
        AND investimento_cofirmado='Sim'";
        $array = array();
        if($dados_pesquisa['user_id']){
            $query .= " AND user_id=?";
            $array[] = $dados_pesquisa['user_id'];
        }
        if($dados_pesquisa['dt_inc']){
            $query .= " AND dt_retorno>=?";
            $array[] = $dados_pesquisa['dt_inc'];
        }
        if($dados_pesquisa['dt_fn']){
            $query .= " AND dt_retorno<=?";
            $array[] = $dados_pesquisa['dt_fn'];
        }

        $query .= " ORDER BY dt_retorno";

        return \DB::select($query,$array);
    }

    public static function get_ganhos_relatorio($dados_pesquisa){
        $query = "SELECT * FROM investimentos WHERE retorno_comissao='Não'
        AND investimento_cofirmado='Sim'";
        $array = array();
        if($dados_pesquisa['user_id']){
            $query .= " AND user_id=?";
            $array[] = $dados_pesquisa['user_id'];
        }
        if($dados_pesquisa['st_investimento']){
            $query .= " AND st_investimento=?";
            $array[] = $dados_pesquisa['st_investimento'];
        }
        if($dados_pesquisa['dt_inc']){
            $query .= " AND dt_retorno>=?";
            $array[] = $dados_pesquisa['dt_inc'];
        }
        if($dados_pesquisa['dt_fn']){
            $query .= " AND dt_retorno<=?";
            $array[] = $dados_pesquisa['dt_fn'];
        }

        $query .= " ORDER BY dt_retorno";

        return \DB::select($query,$array);
    }

    public static function get_investimentos_relatorio($dados_pesquisa){
        $query = "SELECT * FROM investimentos WHERE retorno_comissao='Não'";
        $array = array();
        if($dados_pesquisa['user_id']){
            $query .= " AND user_id=?";
            $array[] = $dados_pesquisa['user_id'];
        }
        if($dados_pesquisa['st_investimento']){
            $query .= " AND st_investimento=?";
            $array[] = $dados_pesquisa['st_investimento'];
        }
        if($dados_pesquisa['reinvestimento']){
            if($dados_pesquisa['reinvestimento'] == 'Sim'){
                $query .= " AND reinvestimento=?";
                $array[] = $dados_pesquisa['reinvestimento'];
            }
            elseif($dados_pesquisa['reinvestimento'] == 'Não'){
                $query .= " AND (reinvestimento=? OR reinvestimento IS NULL)";
                $array[] = $dados_pesquisa['reinvestimento'];
            }
        }
        if($dados_pesquisa['investimento_confirmado']){
            $query .= " AND investimento_confirmado>=?";
            $array[] = $dados_pesquisa['investimento_confirmado'];
        }
        if($dados_pesquisa['dt_investimento_inc']){
            $query .= " AND dt_investimento>=?";
            $array[] = $dados_pesquisa['dt_investimento_inc'];
        }
        if($dados_pesquisa['dt_investimento_fn']){
            $query .= " AND dt_investimento<=?";
            $array[] = $dados_pesquisa['dt_investimento_fn'];
        }
        if($dados_pesquisa['dt_retorno_inc']){
            $query .= " AND dt_retorno>=?";
            $array[] = $dados_pesquisa['dt_retorno_inc'];
        }
        if($dados_pesquisa['dt_retorno_fn']){
            $query .= " AND dt_retorno<=?";
            $array[] = $dados_pesquisa['dt_retorno_fn'];
        }

        $query .= " ORDER BY dt_investimento";

        return \DB::select($query,$array);
    }

    public function get_cotas_investimento(){
        $cotas = CotaInvestimento::where('investimento_id', $this->id)->get();
        $retorno = "";
        foreach($cotas as $linha){
            $retorno .= "<br> Cota ".$linha->cota_id." R$".valorDbForm($linha->vl_investimento);
        }
        $retorno = substr($retorno, 5);
        return $retorno;
    }

}
