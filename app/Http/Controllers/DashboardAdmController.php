<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Investimento;
use App\Models\Resgate;

class DashboardAdmController extends Controller
{
    public function index(){
        //setar o menu
        session()->put('menu_active','menu_dashboard');

        //vamos buscar os investimentos pendentes
        $investimentos = Investimento::where('investimento_cofirmado','<>','Sim')->get();
        $resgates = Resgate::where('resgate_confirmado','<>','Sim')->get();

        return view('adm/dashboard/index', compact('investimentos','resgates'));
    }

    public function confirma_investimento($id){
        $investimento = Investimento::where('id', $id)->first();
        $investimento->investimento_cofirmado = 'Sim';
        $investimento->save();

        ApiZapSignController::create_doc($investimento);

        return redirect()->route('adm.dashboard')->with('mensagem','Investimento Confirmado');
    }

    public function confirma_resgate($id){
        $resgate = Resgate::where('id', $id)->first();
        $resgate->resgate_confirmado = 'Sim';
        $resgate->save();

        return redirect()->route('adm.dashboard')->with('mensagem','Resgate Confirmado');
    }

    public function perfil(){
        //setar o menu
        session()->put('menu_active','menu_dashboard');

        $user = auth()->user();
        return view('adm/dashboard/perfil', compact('user'));
    }

    public function atualizar_foto(Request $request){
        try {
            $user = auth()->user();
            if($request->hasFile('imagem') && $request->file('imagem')->isValid()){
                $imagem = $request->imagem;
                $extensao = $imagem->extension();

                $nm_imagem = $user->id.".".$extensao;
                $request->imagem->move(public_path('img/usuarios'), $nm_imagem);

                $user->imagem = $nm_imagem;
                $user->save();

            }
            return redirect()->route('perfil')->with('mensagem', 'Foto Atualizado!');
        } catch (\Exception $e) {
            return redirect()->route('perfil')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function resetar_foto(){
        try {
            $user = auth()->user();
            $user->imagem = null;
            $user->save();
            return redirect()->route('perfil')->with('mensagem', 'Foto Atualizado!');
        } catch (\Exception $e) {
            return redirect()->route('perfil')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function update(Request $request){
        try {
            $user = auth()->user();
            $user->nome = $request->nome;
            $user->email = $request->email;
            $user->telefone = $request->telefone;
            $user->save();
            return redirect()->route('perfil')->with('mensagem', 'Perfil Atualizado!');
        } catch (\Exception $e) {
            return redirect()->route('perfil')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function alterar_senha(Request $request){
        try {
            $user = auth()->user();
            if(Hash::check($request->password, $user->password)){
                if($request->novo_password == $request->confirma_password){
                    $user->password = bcrypt($request->novo_password);
                    $user->save();
                    return redirect()->route('perfil')->with('mensagem', 'Senha Alterada!');
                }
                else{
                    return redirect()->back()->with('mensagem_erro', "Nova Senha e Confirmar Senha não são iguais");
                }
            }
            else{
                return redirect()->back()->with('mensagem_erro', "Senha Inválido");
            }
        } catch (\Exception $e) {
            return redirect()->route('perfil')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function setar_notificacoes(){
        $user = auth()->user();
        $user->notificacao_email = $_GET['notificacao'];
        $user->save();
    }

    public function setar_lembrete(){
        $user = auth()->user();
        $user->lembrete_vencimento = $_GET['lembrete'];
        $user->save();
    }
}
