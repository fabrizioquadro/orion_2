@extends('layout/login')

@section('conteudo')
<div class="row">
    <div class="col-md-12">
        <div class="p-4">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    @if($mensagem = Session::get('erro'))
                        <div class="alert alert-solid-danger" role="alert">
                            {{ $mensagem }}
                        </div>
                    @endif
                    @if($mensagem = Session::get('mensagem'))
                        <div class="alert alert-solid-success" role="alert">
                            {{ $mensagem }}
                        </div>
                    @endif
                    <h5 class="mb-3 colorAmarelo">Register</h5>
                    <form id="formAuthentication" class="mb-3" action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="row gy-2 mt-1">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="email" class="colorBranco">Email: <span style='color:red'>*</span></label>
                                    <input class="form-control form-control-rounded" required name="email" type="email">
                                </div>
                            </div>
                        </div>
                        <div class="row gy-2 mt-1">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="password" class="colorBranco">Senha: <span style='color:red'>*</span></label>
                                    <input class="form-control form-control-rounded" required name="password" type="password">
                                </div>
                            </div>
                        </div>
                        <div class="row gy-2 mt-1">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="nome" class="colorBranco">Nome: <span style='color:red'>*</span></label>
                                    <input class="form-control form-control-rounded" required name="nome" type="text">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="telefone" class="colorBranco">Telefone:</label>
                                    <input class="form-control form-control-rounded" name="telefone" type="text" onkeypress="mascara( this, mtel )" maxlength="15">
                                </div>
                            </div>
                        </div>
                        <div class="row gy-2 mt-1">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="cpf" class="colorBranco">CPF: <span style='color:red'>*</span></label>
                                    <input class="form-control form-control-rounded" required name="cpf" type="text">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="cep" class="colorBranco">CEP:</label>
                                    <input class="form-control form-control-rounded" name="cep" id="cep" type="text" maxlength="9" onkeypress="formatar('#####-###', this)" >
                                </div>
                            </div>
                        </div>
                        <div class="row gy-2 mt-1">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="endereco" class="colorBranco">Endereço:</label>
                                    <input class="form-control form-control-rounded" name="endereco" id="endereco" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row gy-2 mt-1">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="numero" class="colorBranco">Número:</label>
                                    <input class="form-control form-control-rounded" name="numero" id="numero" type="text">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="complemento" class="colorBranco">Complemento:</label>
                                    <input class="form-control form-control-rounded" name="complemento" id="complemento" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row gy-2 mt-1">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="bairro" class="colorBranco">Bairro:</label>
                                    <input class="form-control form-control-rounded" name="bairro" id="bairro" type="text">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="cidade" class="colorBranco">Cidade:</label>
                                    <input class="form-control form-control-rounded" name="cidade" id="cidade" type="text">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="uf" class="colorBranco">UF:</label>
                                    <input class="form-control form-control-rounded" name="uf" id="uf" type="text" maxlength="2">
                                </div>
                            </div>
                        </div>
                        <div class="row gy-2 mt-1">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="banco" class="colorBranco">Banco:</label>
                                    <input class="form-control form-control-rounded" name="banco" id="banco" type="text">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="tipo_conta" class="colorBranco">Tipo de Conta:</label>
                                    <input class="form-control form-control-rounded" name="tipo_conta" id="tipo_conta" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row gy-2 mt-1">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="agencia" class="colorBranco">Agência:</label>
                                    <input class="form-control form-control-rounded" name="agencia" id="agencia" type="text">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nr_conta" class="colorBranco">Conta:</label>
                                    <input class="form-control form-control-rounded" name="nr_conta" id="nr_conta" type="text">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="chave_pix" class="colorBranco">Chave Pix:</label>
                                    <input class="form-control form-control-rounded" name="chave_pix" id="chave_pix" type="text">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-rounded btn-primary btn-block mt-2">Registrar</button>
                    </form>
                </div>
            </div>
        </div>
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
