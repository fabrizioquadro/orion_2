<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Investidor;
use App\Models\InvestidorArquivo;

class InvestidorController extends Controller
{
    public function index(){
        //setar o menu
        session()->put('menu_active','menu_investidores');

        $users = User::where('tipo','Investidor')->get();
        return view('adm/investidores/index', compact('users'));
    }

    public function adicionar(){
        //setar o menu
        session()->put('menu_active','menu_investidores');
        $investidores = User::whereIn('tipo', ['Investidor','Promotor'])->orderBy('nome')->get();

        return view('adm/investidores/adicionar', compact('investidores'));
    }

    public function insert(Request $request){
        try {
            $dados = $request->only('nome','email','telefone');
            $dados['password'] = bcrypt($request->password);
            $dados['tipo'] = 'Investidor';
            $user = User::create($dados);

            if($request->hasFile('imagem') && $request->file('imagem')->isValid()){
                $imagem = $request->imagem;
                $extensao = $imagem->extension();

                $nm_imagem = $user->id.".".$extensao;
                $request->imagem->move(public_path('img/usuarios'), $nm_imagem);

                $user->imagem = $nm_imagem;
                $user->save();
            }

            //vamos gerar os arquivos enviados
            if($request->hasFile('arquivos')){
                foreach($request->file('arquivos') as $arquivo){
                    if($arquivo->isValid()){
                        $extensao = $arquivo->extension();
                        $nm_arquivo = str_replace(".$extensao", "", $arquivo->getClientOriginalName());
                        $arquivo_link = $arquivo->getClientOriginalName();
                        $arquivo->move(public_path('investidores/'.$user->id."/arquivos/"), $arquivo_link);

                        $dados_arq = [
                            'user_id' => $user->id,
                            'nome' => $nm_arquivo,
                            'arquivo' => $arquivo_link,
                        ];

                        InvestidorArquivo::create($dados_arq);
                    }
                }
            }

            //vamos colocar os dados na tabela investidors
            $dados = $request->only('cpf','cep','endereco','numero','complemento','bairro',
            'cidade','uf','banco','tipo_conta','agencia','nr_conta','chave_pix','user_id_indicador',
            'assinatura_contrato_investimento','st_renda_total');
            $dados['user_id'] = $user->id;
            $investidor = Investidor::create($dados);

            //if($investidor->assinatura_contrato_investimento == 'Não'){
            //    if(!ApiZapSignController::create_doc($investidor)){
            //        return redirect()->route('adm.investidores')->with('mensagem_erro', 'Ocorreu um erro na geração do contrato de investimento.');
            //    }
            //}

            return redirect()->route('adm.investidores')->with('mensagem', 'Investidor Cadastrado');
        } catch (\Exception $e) {
            return redirect()->route('adm.investidores')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function editar($id){
        //setar o menu
        session()->put('menu_active','menu_investidores');

        $user = User::where('id', $id)
        ->where('tipo', 'Investidor')
        ->first();
        $investidores = User::whereIn('tipo', ['Investidor','Promotor'])->orderBy('nome')->get();
        return view('adm/investidores/editar', compact('user','investidores'));
    }

    public function update(Request $request){
        try {
            $dados = $request->only('nome','email','telefone');
            User::where('id', $request->user_id)->update($dados);
            $user = User::where('id', $request->user_id)->first();

            if($request->hasFile('imagem') && $request->file('imagem')->isValid()){
                $imagem = $request->imagem;
                $extensao = $imagem->extension();

                $nm_imagem = $user->id.".".$extensao;
                $request->imagem->move(public_path('img/usuarios'), $nm_imagem);

                $user->imagem = $nm_imagem;
                $user->save();
            }

            //vamos gerar os arquivos enviados
            if($request->hasFile('arquivos')){
                foreach($request->file('arquivos') as $arquivo){
                    if($arquivo->isValid()){
                        $extensao = $arquivo->extension();
                        $nm_arquivo = str_replace(".$extensao", "", $arquivo->getClientOriginalName());
                        $arquivo_link = $arquivo->getClientOriginalName();
                        $arquivo->move(public_path('/investidores/'.$user->id."/arquivos/"), $arquivo_link);

                        $dados_arq = [
                            'user_id' => $user->id,
                            'nome' => $nm_arquivo,
                            'arquivo' => $arquivo_link,
                        ];

                        InvestidorArquivo::create($dados_arq);
                    }
                }
            }

            //vamos colocar os dados na tabela investidors
            $dados = $request->only('cpf','cep','endereco','numero','complemento','bairro',
            'cidade','uf','banco','tipo_conta','agencia','nr_conta','chave_pix','user_id_indicador',
            'assinatura_contrato_investimento','st_renda_total');
            Investidor::where('user_id', $user->id)->update($dados);

            return redirect()->route('adm.investidores')->with('mensagem', 'Investidor Editado');
        } catch (\Exception $e) {
            return redirect()->route('adm.investidores')->with('mensagem_erro', $e->getMessage());
        }

    }

    public function alterar_senha($id){
        //setar o menu
        session()->put('menu_active','menu_investidores');

        $user = User::where('id', $id)
        ->where('tipo', 'Investidor')
        ->first();
        return view('adm/investidores/alterar_senha', compact('user'));
    }

    public function update_senha(Request $request){
        try {
            $user = User::where('id', $request->user_id)->first();
            $user->password = bcrypt($request->password);
            $user->save();

            return redirect()->route('adm.investidores')->with('mensagem', 'Senha Alterada!');
        } catch (\Exception $e) {
            return redirect()->route('adm.investidores')->with('mensagem_erro', $e->getMessage());
        }

    }

    public function excluir($id){
        //setar o menu
        session()->put('menu_active','menu_investidores');

        $user = User::where('id', $id)
        ->where('tipo', 'Investidor')
        ->first();
        return view('adm/investidores/excluir', compact('user'));
    }

    public function delete(Request $request){
        try {
            $user = User::where('id', $request->user_id)->first();
            Investidor::where('user_id', $user->id)->delete();
            InvestidorArquivo::where('user_id', $user->id)->delete();
            $user->delete();

            return redirect()->route('adm.investidores')->with('mensagem', 'Investidor Excluído!');
        } catch (\Exception $e) {
            return redirect()->route('adm.investidores')->with('mensagem_erro', $e->getMessage());
        }

    }

    public function arquivos($id){
        //setar o menu
        session()->put('menu_active','menu_investidores');

        $user = User::where('id', $id)
        ->where('tipo', 'Investidor')
        ->first();
        return view('adm/investidores/arquivos', compact('user'));
    }

    public function arquivos_delete($id){
        try {
            $arquivo = InvestidorArquivo::where('id',$id)->first();
            $arquivo->delete();
            return redirect()->route('adm.investidores.arquivos', $arquivo->user_id);
        } catch (\Exception $e) {
            return redirect()->route('adm.investidores', $e->getMessage());
        }
    }

    public function arquivos_update(Request $request){
        try {
            if($request->hasFile('arquivos')){
                $user = User::where('id', $request->user_id)->first();
                foreach($request->file('arquivos') as $arquivo){
                    if($arquivo->isValid()){
                        $extensao = $arquivo->extension();
                        $nm_arquivo = str_replace(".$extensao", "", $arquivo->getClientOriginalName());
                        $arquivo_link = $arquivo->getClientOriginalName();
                        $arquivo->move(public_path('/investidores/'.$user->id."/arquivos/"), $arquivo_link);

                        $dados_arq = [
                            'user_id' => $user->id,
                            'nome' => $nm_arquivo,
                            'arquivo' => $arquivo_link,
                        ];

                        InvestidorArquivo::create($dados_arq);
                    }
                }
                return redirect()->route('adm.investidores.arquivos', $user->id);
            }
        } catch (\Exception $e) {
            return redirect()->route('adm.investidores', $e->getMessage());
        }

    }
}
