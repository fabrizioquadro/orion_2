<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotaInvestimento extends Model
{
    use HasFactory;

    protected $fillable = [
        'cota_id',
        'investimento_id',
        'vl_investimento',
    ];

    public function investimento(){
        return $this->belongsTo(Investimento::class);
    }

    public function cota(){
        return $this->belongsTo(Investimento::class);
    }
}
