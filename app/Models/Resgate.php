<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resgate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'dt_resgate',
        'vl_resgate',
        'resgate_confirmado',
        'cota_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function get_saques_relatorio($dados_pesquisa){
        $query = "SELECT * FROM resgates WHERE resgate_confirmado='Sim'";
        $array = array();
        if($dados_pesquisa['user_id']){
            $query .= " AND user_id=?";
            $array[] = $dados_pesquisa['user_id'];
        }
        if($dados_pesquisa['dt_inc']){
            $query .= " AND dt_resgate>=?";
            $array[] = $dados_pesquisa['dt_inc'];
        }
        if($dados_pesquisa['dt_fn']){
            $query .= " AND dt_resgate<=?";
            $array[] = $dados_pesquisa['dt_fn'];
        }

        $query .= " ORDER BY dt_resgate";

        return \DB::select($query,$array);
    }
}
