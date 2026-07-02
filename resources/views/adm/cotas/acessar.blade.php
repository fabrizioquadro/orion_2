@extends('layout.sistema')

@section('conteudo')
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
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-text-box-check-outline"></i>
                Acessar Cota
            </h4>
        </div>
        <hr>
        <div class="row gy-4 mt-2">
            <div class="col-md-3 form-group">
                <label for="vl_carta">Código:</label><br>
                <b>{{ $cota->codigo }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="vl_carta">Valor da Carta:</label><br>
                <b>R$ {{ valorDbForm($cota->vl_carta) }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="parcelas">Número de Parcelas:</label><br>
                <b>{{ $cota->parcelas }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="vl_parcela">Valor da Parcela:</label><br>
                <b>R$ {{ valorDbForm($cota->vl_parcela) }}</b>
            </div>
        </div>
        <div class="row gy-4 mt-2">
            <div class="col-md-3 form-group">
                <label for="dt_compra">Data da Compra:</label><br>
                <b>{{ dataDbForm($cota->dt_compra) }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="vl_compra">Valor da Compra:</label><br>
                <b>R$ {{ valorDbForm($cota->vl_compra) }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="dt_venda">Data da Venda:</label><br>
                <b>{{ dataDbForm($cota->dt_venda) }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="vl_venda">Valor da Venda:</label><br>
                <b>R$ {{ valorDbForm($cota->vl_venda) }}</b>
            </div>
        </div>
        <div class="row gy-4 mt-2">
            <div class="col-md-2 form-group">
                <label for="vl_venda">Dias:</label><br>
                <b>{{ $cota->dias_venda }}</b>
            </div>
            <div class="col-md-2 form-group">
                <label for="vl_venda">Situação:</label><br>
                <b>{{ $cota->situacao }}</b>
            </div>
            <div class="col-md-2 form-group">
                <label for="vl_venda">Lucro Bruto:</label><br>
                <b>R$ {{ valorDbForm($cota->lucro_bruto) }}</b>
            </div>
            <div class="col-md-2 form-group">
                <label for="vl_venda">Lucro Ratiado:</label><br>
                <b>R$ {{ valorDbForm($cota->lucro_ratiado) }}</b>
            </div>
            <div class="col-md-2 form-group">
                <label for="vl_venda">Comissão:</label><br>
                <b>R$ {{ valorDbForm($cota->comissao) }}</b>
            </div>
            <div class="col-md-2 form-group">
                <label for="vl_venda">Comissão Orion:</label><br>
                <b>R$ {{ valorDbForm($cota->comissao_orion) }}</b>
            </div>
            <div class="col-md-2 form-group">
                <label for="vl_venda">Lucro Liquido:</label><br>
                <b>R$ {{ valorDbForm($cota->lucro_liquido) }}</b>
            </div>
        </div>
        <div class="row gy-4 mt-2">
            <div class="col-md-12 form-group">
                <label for="obs">Observação:</label><br>
                <b>{{ $cota->obs }}</b>
            </div>
        </div>
    </div>
</div>
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-hand-coin-outline"></i>
                Investimentos na Cota
            </h4>
        </div>
        <hr>
        <div class="row gy-4 mt-2">
            <table class="table table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Investimento</th>
                        <th>V. Aportado/V. Invest</th>
                    </tr>
                </thead>
                <tbody id="tabela_investimentos">
                    @php
                    $total_investimento = 0;
                    @endphp
                    @foreach($cota->investimentos as $aporte)
                        @php
                        $total_investimento += $aporte->vl_investimento;
                        @endphp
                        <tr>
                            <td>{{ $aporte->investimento->user->nome." ".$aporte->investimento->titulo." - Retorno: ".dataDbForm($aporte->investimento->dt_retorno) }}</td>
                            <td>R$ {{ valorDbForm($aporte->vl_investimento)." / R$ ".valorDbForm($aporte->investimento->vl_investimento) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td> <b>Total</b> </td>
                        <td> <b>R$ {{ valorDbForm($total_investimento) }}</b> </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-cash"></i>
                Rateio Lucro
            </h4>
        </div>
        <hr>
        <div class="row gy-4 mt-2">
            <table class="table table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Investimento</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($array_rateio as $linha)
                        <tr>
                            <td>{{ $linha['investidor'] }}</td>
                            <td>{{ $linha['descricao'] }}</td>
                            <td>R$ {{ valorDbForm($linha['rateio']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($cota->situacao == "Aberta")
    <div class="card card-border-shadow-primary mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h4 class="card-title">
                    <i class="menu-icon tf-icons mdi mdi-cash"></i>
                    Finalizar Cota
                </h4>
            </div>
            <form id="formulario" action="{{ route('adm.cotas.finalizar') }}" method="post">
                @csrf
                <input type="hidden" name="cota_id" value="{{ $cota->id }}">
                <hr>
                <div class="row align-items-end gy-4 mt-2">
                    <div class="col-md-3">
                        <div class="form-floating form-floating-outline">
                            <input required class="form-control" type="date" id="dt_venda" name="dt_venda" value="{{ $cota->dt_venda }}"/>
                            <label for="dt_venda">Data da Venda:</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating form-floating-outline">
                            <input required class="form-control" type="text" id="vl_venda" name="vl_venda" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="{{ valorDbForm($cota->vl_venda) }}"/>
                            <label for="vl_venda">Valor da Venda:</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary me-2" id="botao_finalizar_cota">Finalizar Cota</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        document.getElementById('botao_finalizar_cota').addEventListener('click', ()=>{
            if({{ $total_investimento }} == {{ $cota->vl_compra }}){
                dt_venda = document.getElementById('dt_venda').value;
                vl_venda = document.getElementById('vl_venda').value;
                if(dt_venda != "" && vl_venda != ""){
                    document.getElementById('formulario').submit();
                }
                else{
                    alert('É preciso confirmar a data da venda e o valor.');
                }
            }
            else{
                alert('Valores de investimento na cota e valor de compra da cota não são iguais');
            }
        })
    </script>
@else
    <form id="formulario_abrir" action="{{ route('adm.cotas.abrir') }}" method="post">
        @csrf
        <input type="hidden" name="cota_id" value="{{ $cota->id }}">
    </form>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary me-2" id="botao_abrir_cota">Desfazer Finalizar</button>
        </div>
    </div>
    <script type="text/javascript">
        document.getElementById('botao_abrir_cota').addEventListener('click', ()=>{
            if(confirm('Tem certeza que deseja abrir novamente esta cota? As ações de fecgamento serão desfeitas')){
                document.getElementById('formulario_abrir').submit();
            }
        });
    </script>
@endif
@endsection
