<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cota extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'vl_carta',
        'parcelas',
        'vl_parcela',
        'dt_compra',
        'dt_venda',
        'dias_venda',
        'vl_compra',
        'vl_venda',
        'lucro_bruto',
        'lucro_ratiado',
        'comissao',
        'comissao_orion',
        'aplica_comissao_orion',
        'lucro_liquido',
        'situacao',
        'obs',
    ];

    public function investimentos(){
        return $this->hasMany(CotaInvestimento::class);
    }

    public static function get_cotas_relatorio($dados_pesquisa){
        $query = "SELECT * FROM cotas WHERE 1=1";
        $array = array();
        if($dados_pesquisa['dt_compra_inc']){
            $query .= " AND dt_compra >= ?";
            $array[] = $dados_pesquisa['dt_compra_inc'];
        }
        if($dados_pesquisa['dt_compra_fn']){
            $query .= " AND dt_compra <= ?";
            $array[] = $dados_pesquisa['dt_compra_fn'];
        }
        if($dados_pesquisa['dt_venda_inc']){
            $query .= " AND dt_venda >= ?";
            $array[] = $dados_pesquisa['dt_venda_inc'];
        }
        if($dados_pesquisa['dt_venda_fn']){
            $query .= " AND dt_venda <= ?";
            $array[] = $dados_pesquisa['dt_venda_fn'];
        }
        if($dados_pesquisa['situacao']){
            $query .= " AND situacao <= ?";
            $array[] = $dados_pesquisa['situacao'];
        }

        $query .= " ORDER BY codigo";

        return \DB::select($query, $array);
    }
}
