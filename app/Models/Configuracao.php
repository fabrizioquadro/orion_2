<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'tempo_investimento',
        'minimo_investimento',
        'ate_ciquenta',
        'ate_cem',
        'acima_cem',
        'modelo_contrato',
    ];
}
