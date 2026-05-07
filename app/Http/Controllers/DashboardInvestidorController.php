<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investimento;

class DashboardInvestidorController extends Controller
{
    public function index(){
        //setar o menu
        session()->put('menu_active','menu_dashboard');

        $user = auth()->user();

        //vamos buscar o total investido
        $total_investido = Investimento::where('user_id', $user->id)
        ->where('investimento_cofirmado','Sim')
        ->whereNull('reinvestimento')
        ->sum('vl_investimento');

        //vamos buscar o ganho total
        $total_ganho = Investimento::where('user_id', $user->id)
        ->where('investimento_cofirmado','Sim')
        ->sum('vl_retorno_ganho');

        //vamos buscar o investimentos ativos
        $investimentos = Investimento::where('user_id', $user->id)
        ->where('investimento_cofirmado','Sim')
        ->where('st_investimento', 'Ativo')
        ->get();
        //vamos buscar o proximo vencimento
        $invest_proximo = Investimento::where('user_id', $user->id)
        ->where('investimento_cofirmado','Sim')
        ->where('st_investimento', 'Ativo')
        ->orderBy('dt_retorno')
        ->first();

        $porcentagem_ganho = $total_investido > 0 ? round($total_ganho * 100 / $total_investido, 2) : '0';

        return view('investidor/dashboard/index', compact('total_investido',
        'total_ganho','investimentos','invest_proximo','porcentagem_ganho'));
    }
}
