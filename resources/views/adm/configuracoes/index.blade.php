@extends('layout.sistema')

@section('conteudo')
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.css" />
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <h4 class="card-title">
            <i class="menu-icon tf-icons mdi mdi-cog"></i>
            Configurações
        </h4>
        @if($mensagem = Session::get('mensagem'))
        <div class="alert alert-success" role="alert">
            {{ $mensagem }}
        </div>
        @endif
        @if($mensagem = Session::get('mensagem_erro'))
        <div class="alert alert-danger" role="alert">
            {{ $mensagem }}
        </div>
        @endif
        <hr>
        <form action="{{ route('adm.configuracoes.setar') }}" method="post">
            @csrf
            <h5 class="card-title">Geral</h5>
            <div class="row mt-2 gy-4">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="number" id="tempo_investimento" name="tempo_investimento" value="{{ $config->tempo_investimento }}"/>
                        <label for="tempo_investimento">Tempo dos Investimentos(dias):</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="minimo_investimento" name="minimo_investimento" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="{{ valorDbForm($config->minimo_investimento) }}"/>
                        <label for="minimo_investimento">Valor Mínimo de Investimento:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="ate_ciquenta" name="ate_ciquenta" value="{{ $config->ate_ciquenta }}"/>
                        <label for="ate_ciquenta">Porcentagem de ganho até R$ 50.000,00:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="ate_cem" name="ate_cem" value="{{ $config->ate_cem }}"/>
                        <label for="ate_cem">Porcentagem de ganho de R$ 50.000,01 á R$ 100.000,00:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="acima_cem" name="acima_cem" value="{{ $config->acima_cem }}"/>
                        <label for="acima_cem">Porcentagem de ganho acima de R$ 100.000,00:</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2 gy-4">
                <div class="col-md-12 form-group">
                    <button type="submit" class="btn btn-primary me-2">Salvar</button>
                </div>
            </div>
        </form>
        <hr class="mt-5">
        <form action="{{ route('adm.configuracoes.contrato') }}" method="post">
            @csrf
            <h5 class="card-title">Contrato Investidor</h5>
            <div class="row mt-2 gy-4">
                <div class="col-md-9">
                    <div class="form-group">
                        <textarea name="modelo_contrato" id="modelo_contrato">{!! $config->modelo_contrato !!}</textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <h6 class="card-title">Macros</h6>
                    <div class="demo-inline-spacing mt-3">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                #nome#
                                <span>Nome</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                #email#
                                <span>Email</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                #telefone#
                                <span>Telefone</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                #cpf#
                                <span>CPF</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                #cep#
                                <span>CEP</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                #endereco#
                                <span>Endereço</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                #numero#
                                <span>Numero</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                #bairro#
                                <span>Bairro</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                #cidade#
                                <span>Cidade</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                #uf#
                                <span>UF</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                #banco#
                                <span>Banco</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span style='font-size: 14px'>#tipo_conta#</span>
                                <span style='font-size: 14px'>Tipo da Conta</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                #agencia#
                                <span>Agencia</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                #nr_conta#
                                <span>Nr Conta</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                #chave_pix#
                                <span>Chave Pix</span>
                            </li>
                        </ul>
                      </div>
                </div>
            </div>
            <div class="row mt-2 gy-4">
                <div class="col-md-12 form-group">
                    <button type="submit" class="btn btn-primary me-2">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.0/"
        }
    }
</script>
<script type="module">
    import {
        ClassicEditor,
        Essentials,
        Bold,
        Italic,
        Font,
        Alignment,
        Paragraph
    } from 'ckeditor5';

    ClassicEditor.create( document.querySelector( '#modelo_contrato' ), {
        plugins: [ Essentials, Bold, Italic, Font, Paragraph, Alignment ],
        toolbar: {
            items: [
                'undo', 'redo', '|', 'bold', 'italic', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor','Alignment'
            ]
        }
    }).catch( error => {
        console.error( error );
    });
</script>
@endsection
