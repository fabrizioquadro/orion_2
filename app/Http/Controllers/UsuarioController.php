<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Investidor;

class UsuarioController extends Controller
{
    public function index(){
        //setar o menu
        session()->put('menu_active','menu_usuarios');

        $users = User::where('tipo','<>','Investidor')->get();
        return view('adm/usuarios/index', compact('users'));
    }

    public function adicionar(){
        //setar o menu
        session()->put('menu_active','menu_usuarios');

        return view('adm/usuarios/adicionar');
    }

    public function insert(Request $request){
        try {
            $dados = $request->except('_token','imagem','password','indicacao_anderson');
            $dados['password'] = bcrypt($request->password);
            $user = User::create($dados);

            if($request->tipo == "Promotor"){
                //se entrar aqui vamos cadastrar o usuario na tabela investidores
                $dados = [
                    'user_id' => $user->id,
                ];
                if($request->indicacao_anderson == 'Sim'){
                    $dados['user_id_indicador'] = 3;
                }

                Investidor::create($dados);
            }

            if($request->hasFile('imagem') && $request->file('imagem')->isValid()){
                $imagem = $request->imagem;
                $extensao = $imagem->extension();

                $nm_imagem = $user->id.".".$extensao;
                $request->imagem->move(public_path('img/usuarios'), $nm_imagem);

                $user->imagem = $nm_imagem;
                $user->save();
            }

            return redirect()->route('adm.usuarios')->with('mensagem', 'Usuário Cadastrado');
        } catch (\Exception $e) {
            return redirect()->route('adm.usuarios')->with('mensagem_erro', $e->getMessage());
        }

    }

    public function editar($id){
        //setar o menu
        session()->put('menu_active','menu_usuarios');

        $user = User::where('id', $id)->first();
        return view('adm/usuarios/editar', compact('user'));
    }

    public function update(Request $request){
        try {
            $dados = $request->except('_token','imagem','user_id','indicacao_anderson');
            User::where('id', $request->user_id)->update($dados);
            $user = User::where('id', $request->user_id)->first();

            Investidor::where('user_id', $user->id)->delete();

            if($request->tipo == "Promotor"){
                //se entrar aqui vamos cadastrar o usuario na tabela investidores
                $dados = [
                    'user_id' => $user->id,
                ];
                if($request->indicacao_anderson == 'Sim'){
                    $dados['user_id_indicador'] = 3;
                }

                Investidor::create($dados);
            }

            if($request->hasFile('imagem') && $request->file('imagem')->isValid()){
                $imagem = $request->imagem;
                $extensao = $imagem->extension();

                $nm_imagem = $user->id.".".$extensao;
                $request->imagem->move(public_path('img/usuarios'), $nm_imagem);

                $user->imagem = $nm_imagem;
                $user->save();
            }

            return redirect()->route('adm.usuarios')->with('mensagem', 'Usuário Editado');
        } catch (\Exception $e) {
            return redirect()->route('adm.usuarios')->with('mensagem_erro', $e->getMessage());
        }

    }

    public function alterar_senha($id){
        //setar o menu
        session()->put('menu_active','menu_usuarios');

        $user = User::where('id', $id)->first();
        return view('adm/usuarios/alterar_senha', compact('user'));
    }

    public function update_senha(Request $request){
        try {
            $user = User::where('id', $request->user_id)->first();
            $user->password = bcrypt($request->password);
            $user->save();

            return redirect()->route('adm.usuarios')->with('mensagem', 'Senha Alterada!');
        } catch (\Exception $e) {
            return redirect()->route('adm.usuarios')->with('mensagem_erro', $e->getMessage());
        }

    }

    public function excluir($id){
        //setar o menu
        session()->put('menu_active','menu_usuarios');

        $user = User::where('id', $id)->first();
        return view('adm/usuarios/excluir', compact('user'));
    }

    public function delete(Request $request){
        try {
            $user = User::where('id', $request->user_id)->first();
            Investidor::where('user_id', $user->id)->delete();
            $user->delete();

            return redirect()->route('adm.usuarios')->with('mensagem', 'Usuário Excluído!');
        } catch (\Exception $e) {
            return redirect()->route('adm.usuarios')->with('mensagem_erro', $e->getMessage());
        }

    }
}
