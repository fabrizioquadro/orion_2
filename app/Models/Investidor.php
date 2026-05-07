<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investidor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cpf',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'banco',
        'tipo_conta',
        'agencia',
        'nr_conta',
        'chave_pix',
        'token_doc_zap_sign',
        'assinatura_contrato_investimento',
        'user_id_indicador',
        'st_renda_total',
    ];

    public function indicacao(){
        return $this->belongsTo(User::class, 'user_id_indicador', 'id');
    }
}
