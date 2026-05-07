@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-swap-horizontal"></i>
                Transações
            </h4>
            <div>
                @if($valor_disponivel > 0)
                    <button type="button" id="botao_reinvestir" class="btn btn-sm btn-primary">Reinvestir</button>
                    <button type="button" id="botao_resgate" class="btn btn-sm btn-danger">Resgatar</button>
                @endif
            </div>
        </div>
        <hr>
        @if($mensagem = Session::get('mensagem'))
            <div class="alert alert-success alert-dismissible mt-3" role="alert">
                {{ $mensagem }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($mensagem = Session::get('mensagem_erro'))
            <div class="alert alert-danger alert-dismissible mt-3" role="alert">
                {{ $mensagem }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row mb-3 gy-4">
            <div class="col-sm-12 col-lg-4 mb-4">
                <div class="card card-border-shadow-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-success"><i class="mdi mdi-format-list-bulleted mdi-20px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0 display-6">R$ {{ valorDbForm($total_aportes) }}</h4>
                        </div>
                        <p class="mb-0 text-heading">Valores Aportados</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-4 mb-4">
                <div class="card card-border-shadow-danger h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-danger"><i class="mdi mdi-format-list-bulleted mdi-20px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0 display-6">R$ {{ valorDbForm($total_resgates) }}</h4>
                        </div>
                        <p class="mb-0 text-heading">Valores Resgatados</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-4 mb-4">
                <div class="card card-border-shadow-info h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-info"><i class="mdi mdi-format-list-bulleted mdi-20px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0 display-6">R$ {{ valorDbForm($valor_disponivel) }}</h4>
                        </div>
                        <p class="mb-0 text-heading">Valores Disponíveis</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Situação</th>
                        <th>Operação</th>
                        <th>Valor</th>
                        <th>Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($array_transacoes as $linha)
                        @php
                        if($linha[3] == "Entrada"){
                            $color = 'green';
                            $saldo += $linha[4];
                        }
                        elseif($linha[3] == "Reinvestimento"){
                            $color = 'blue';
                        }
                        else{
                            $color = 'red';
                            $saldo -= $linha[4];
                        }
                        @endphp
                        <tr>
                            <td>{{ $linha[1] }}</td>
                            <td>{{ $linha[2] }}</td>
                            <td>{{ $linha[5] }}</td>
                            <td style="color: {{$color}}">{{ $linha[3] }}</td>
                            <td style="color: {{$color}}">R$ {{ valorDbForm($linha[4]) }}</td>
                            <td>R$ {{ valorDbForm($saldo) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_reinvestir" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id='form_reinvestir' action="{{ route('investidor.transacoes.reinvestir') }}" class="modal-content" method="post">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <div class="modal-header">
                <h5 class="modal-title" id="backDropModalTitle">Reinvestir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gy-4 mt-2">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input required class="form-control" type="date" id="dt_investimento" name="dt_investimento"/>
                            <label for="dt_investimento">Data Investimento:</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input required class="form-control" type="text" id="vl_investimento" name="vl_investimento" onkeypress="return(MascaraMoeda(this,'.',',',event))"/>
                            <label for="vl_investimento">Valor Investimento:</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="text" id="titulo" name="titulo"/>
                            <label for="titulo">Título:</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 mt-3">
                    <button class="btn btn-primary" type="button" id="botao_cadastrar_reinvestir">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal_resgatar" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_resgatar" action="{{ route('investidor.transacoes.resgatar') }}" class="modal-content" method="post">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <div class="modal-header">
                <h5 class="modal-title" id="backDropModalTitle">Reinvestir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gy-4 mt-2">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input required class="form-control" type="date" id="dt_resgate" name="dt_resgate"/>
                            <label for="dt_resgate">Data Resgate:</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input required class="form-control" type="text" id="vl_resgate" name="vl_resgate" onkeypress="return(MascaraMoeda(this,'.',',',event))"/>
                            <label for="vl_resgate">Valor Resgate:</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating form-floating-outline">
                            <input required class="form-control" type="text" id="descricao" name="descricao"/>
                            <label for="descricao">Descrição:</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 mt-3">
                    <button class="btn btn-danger" type="button" id="botao_cadastrar_resgate">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
var modalReinvestir;
var modalResgatar;

document.getElementById('botao_reinvestir').addEventListener('click', ()=>{
    modalReinvestir = new bootstrap.Modal(document.getElementById('modal_reinvestir'));
    modalReinvestir.show();
})

document.getElementById('botao_resgate').addEventListener('click', ()=>{
    modalResgatar = new bootstrap.Modal(document.getElementById('modal_resgatar'));
    modalResgatar.show();
})

document.getElementById('botao_cadastrar_reinvestir').addEventListener('click', ()=>{
    data = document.getElementById('dt_investimento').value;
    valor = document.getElementById('vl_investimento').value;
    titulo = document.getElementById('titulo').value;

    if(data && valor && titulo){
        valor_disponivel = parseFloat({{ $valor_disponivel }});
        valor = valor.replace('.','');
        valor = parseFloat(valor.replace(',','.'));
        if(valor > valor_disponivel){
            alert('Valor informado maior que o disponível');
        }
        else{
            document.getElementById('form_reinvestir').submit();
        }
    }
    else{
        alert('É necessario preencher todos os campos');
    }
})

document.getElementById('botao_cadastrar_resgate').addEventListener('click', ()=>{
    data = document.getElementById('dt_resgate').value;
    valor = document.getElementById('vl_resgate').value;
    titulo = document.getElementById('descricao').value;

    if(data && valor && titulo){
        valor_disponivel = parseFloat({{ $valor_disponivel }});
        valor = valor.replace('.','');
        valor = parseFloat(valor.replace(',','.'));
        if(valor > valor_disponivel){
            alert('Valor informado maior que o disponível');
        }
        else{
            document.getElementById('form_resgatar').submit();
        }
    }
    else{
        alert('É necessario preencher todos os campos');
    }
})

</script>

@endsection
