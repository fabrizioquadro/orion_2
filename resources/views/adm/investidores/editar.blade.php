@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-account-group"></i>
                Editar Investidor
            </h4>
        </div>
        <hr>
        <form action="{{ route('adm.investidores.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <div class="row mt-2 gy-4">
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="nome" name="nome" value="{{ $user->nome }}"/>
                        <label for="nome">Nome:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="email" id="email" name="email" value="{{ $user->email }}"/>
                        <label for="email">E-mail:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <select  id="user_id_indicador" name='user_id_indicador' class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($investidores as $investidor)
                                <option @if($user->investidor->user_id_indicador == $investidor->id) selected @endif value="{{ $investidor->id }}">{{ $investidor->nome }}</option>
                            @endforeach
                        </select>
                        <label for="user_id_indicador">Indicado por:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="telefone" name="telefone" maxlength="15" onkeypress="mascara( this, mtel )" value="{{ $user->telefone }}"/>
                        <label for="telefone">Telefone:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="file" id="imagem" name="imagem"/>
                        <label for="imagem">Imagem:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="file" multiple id="arquivos" name="arquivos[]"/>
                        <label for="arquivos">Arquivos:</label>
                    </div>
                </div>
            </div>
            <hr>
            <h6 class="card-title">Dados do Investidos</h6>
            <div class="row mt-2 gy-4">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="cpf" name="cpf" maxlength="14" onkeypress="formatar('###.###.###-##', this)" value="{{ $user->investidor->cpf }}"/>
                        <label for="cpf">CPF:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="cep" name="cep" maxlength="9" onkeypress="formatar('#####-###', this)" value="{{ $user->investidor->cep }}"/>
                        <label for="cep">CEP:</label>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="endereco" name="endereco" value="{{ $user->investidor->endereco }}"/>
                        <label for="endereco">Endereço:</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="numero" name="numero" value="{{ $user->investidor->numero }}"/>
                        <label for="numero">Número:</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="complemento" name="complemento" value="{{ $user->investidor->complemento }}"/>
                        <label for="complemento">Complemento:</label>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="bairro" name="bairro" value="{{ $user->investidor->bairro }}"/>
                        <label for="bairro">Bairro:</label>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="cidade" name="cidade" value="{{ $user->investidor->cidade }}"/>
                        <label for="cidade">Cidade:</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="uf" name="uf" maxlength="2" value="{{ $user->investidor->uf }}"/>
                        <label for="uf">UF:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="banco" name="banco" value="{{ $user->investidor->banco }}"/>
                        <label for="banco">Banco:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="tipo_conta" name="tipo_conta" value="{{ $user->investidor->tipo_conta }}"/>
                        <label for="tipo_conta">Tipo de Conta:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="agencia" name="agencia" value="{{ $user->investidor->agencia }}"/>
                        <label for="agencia">Agencia:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="nr_conta" name="nr_conta" value="{{ $user->investidor->nr_conta }}"/>
                        <label for="nr_conta">Número Conta:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="chave_pix" name="chave_pix" value="{{ $user->investidor->chave_pix }}"/>
                        <label for="chave_pix">Chave Pix:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <select  id="assinatura_contrato_investimento" name='assinatura_contrato_investimento' class="select2 form-select">
                            <option value="">Opções</option>
                            <option @if($user->investidor->assinatura_contrato_investimento == 'Não') selected @endif value="Não">Não</option>
                            <option @if($user->investidor->assinatura_contrato_investimento == 'Sim') selected @endif value="Sim">Sim</option>
                        </select>
                        <label for="assinatura_contrato_investimento">Assinatura do Contrato:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <select required id="st_renda_total" name='st_renda_total' class="select2 form-select">
                            <option @if($user->investidor->st_renda_total == "Não") selected @endif value="Não">Não</option>
                            <option @if($user->investidor->st_renda_total == "Sim") selected @endif value="Sim">Sim</option>
                        </select>
                        <label for="st_renda_total">Renda Total:</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Salvar</button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
document.getElementById('cep').addEventListener('blur', (e)=>{
    var valor = e.target.value;
    var cep = valor.replace(/\D/g, '');
    if (cep != ""){
        var validacep = /^[0-9]{8}$/;
        if(validacep.test(cep)){
            var url = `https://viacep.com.br/ws/${cep}/json/`;
            fetch(url).then(response => response.json()).then(json => {
                if( json.logradouro ){
                    document.getElementById('endereco').value = json.logradouro;
                    document.getElementById('bairro').value = json.bairro;
                    document.getElementById('cidade').value = json.localidade;
                    document.getElementById('uf').value = json.uf;
                }
            });
        }
    }
});
</script>
@endsection
