<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Investidor;
use App\Models\Investimento;

class LoginController extends Controller
{
    public function index(){
        return view('login/index');
    }

    public function login(Request $request){
        $dados = $request->except('_token');
        if(Auth::attempt($dados)){
            $request->session()->regenerate();
            if(auth()->user()->tipo == "Administrador"){
                return redirect()->route('adm.dashboard');
            }
            elseif(auth()->user()->tipo == "Investidor"){
                $investidor = Investidor::where('user_id', auth()->user()->id)->first();
                return redirect()->route('investidor.dashboard');
            }
        }
        else{
            return redirect()->back()->with('erro', "Email ou senha inválidos");
        }
    }

    public function esqueceu_senha(){
        return view('login/esqueceu_senha');
    }

    public function recuperar_senha(Request $request){
        $user = User::where('email', $request->get('email'))->first();
        if($user){
            $novaSenha = createPassword(8, true, true, true, false);
            $user->password = bcrypt($novaSenha);
            $user->save();

            $mensagem = "
            <h4>Nova Senha de Acesso ao Clube Orion Prime - Sistema Online</h4>
            <p>
                Foi alterado por sua solicitação a senha de acesso ao sistema.
            </p>
            <p>
                Sua nova senha é: $novaSenha
            </p>
            ";

            enviarMail($user->email, 'Nova Senha de Acesso', $mensagem);

            return redirect()->route('index')->with('mensagem','Sua nova senha foi enviado para o seu email.');
        }
        else{
            return redirect()->back()->with('erro', "Email inválido");
        }
    }

    public function logout(Request $request){
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }

    public function teste(){
        $investimentos = Investimento::all();

        foreach($investimentos as $investimento){
            $investimento->vl_cota_investido = 0;
            $investimento->vl_cota_restante = $investimento->vl_investimento;

            $investimento->save();
        }



    }

    public function cadastrar(){
        return view('login/cadastrar');
    }

    public function register(Request $request){
        try {
            $dados = $request->only('nome','email','telefone');
            $dados['password'] = bcrypt($request->password);
            $dados['tipo'] = 'Investidor';
            $user = User::create($dados);

            $dados = $request->only('cpf','cep','endereco','numero','complemento','bairro',
            'cidade','uf','banco','tipo_conta','agencia','nr_conta','chave_pix');
            $dados['user_id'] = $user->id;
            $dados['assinatura_contrato_investimento'] = 'Não';
            $investidor = Investidor::create($dados);

            //if(!ApiZapSignController::create_doc($investidor)){
            //    return redirect()->route('index')->with('erro', 'Ocorreu um erro na geração do contrato de investimento.');
            //}

            return redirect()->route('index')->with('mensagem', 'Seu registro foi cadastrado, assine o contrato que foi enviado para o seu email para liberar acesso ao sistema.');
        }catch(\Exception $e){
            return redirect()->route('index')->with('erro', $e->getMessage());
        }

    }
}
