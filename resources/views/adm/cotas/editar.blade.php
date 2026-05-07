@extends('layout.sistema')

@section('conteudo')
<style media="screen">
    .form-select, .input-group-text{
    height: 45px !important;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background: none; /* Remove a seta */
  }
</style>

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
<form id="formulario" action="{{ route('adm.cotas.update') }}" method="post">
    @csrf
    <input type="hidden" name="cota_id" value="{{ $cota->id }}">
    <input type="hidden" name="contador_investimentos" id="contador_investimentos" value="0">
    <input type="hidden" name="investimentos_excluir" id="investimentos_excluir">
    <div class="card card-border-shadow-primary mb-4">
        <div class="card-body">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-text-box-check-outline"></i>
                Editar Cota
            </h4>
            <hr>
            <div class="row gy-4 mt-2">
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="codigo" name="codigo" value="{{ $cota->codigo }}"/>
                        <label for="codigo">Código:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="vl_carta" name="vl_carta" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="{{ valorDbForm($cota->vl_carta) }}"/>
                        <label for="vl_carta">Valor da Carta:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="number" id="parcelas" name="parcelas" value="{{ $cota->parcelas }}"/>
                        <label for="parcelas">Número de Parcelas:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="vl_parcela" name="vl_parcela" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="{{ valorDbForm($cota->vl_parcela) }}"/>
                        <label for="vl_parcela">Valor da Parcela:</label>
                    </div>
                </div>
            </div>
            <div class="row gy-4 mt-2">
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="date" id="dt_compra" name="dt_compra"  value="{{ $cota->dt_compra }}"/>
                        <label for="dt_compra">Data da Compra:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="vl_compra" name="vl_compra" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="{{ valorDbForm($cota->vl_compra) }}"/>
                        <label for="vl_compra">Valor da Compra:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="date" id="dt_venda" name="dt_venda"  value="{{ $cota->dt_venda }}"/>
                        <label for="dt_venda">Data da Venda:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="vl_venda" name="vl_venda" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="{{ valorDbForm($cota->vl_venda) }}"/>
                        <label for="vl_venda">Valor da Venda:</label>
                    </div>
                </div>
            </div>
            <div class="row gy-4 mt-2">
                <div class="col-md-12">
                    <div class="form-floating form-floating-outline mb-4">
                        <textarea class="form-control h-px-100" id="obs" name="obs">{{ $cota->obs }}</textarea>
                        <label for="obs">Observação:</label>
                    </div>
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
            <div class="row align-items-end">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <select id="investimento_id" name='investimento_id' class="form-select combobox">
                            <option value="">Opções</option>
                            @foreach($investimentos as $investimento)
                                <option data-valor='{{ $investimento->vl_cota_restante }}' value="{{ $investimento->id }}">{{ $investimento->user->nome." ".$investimento->titulo." - Retorno: ".dataDbForm($investimento->dt_retorno)." - Valor Disponível: R$".valorDbForm($investimento->vl_cota_restante) }}</option>
                            @endforeach
                        </select>
                        <label for="investimento_id">Investimento:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="vl_investimento" name="vl_investimento" onkeypress="return(MascaraMoeda(this,'.',',',event))"/>
                        <label for="vl_investimento">Valor Investimento:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <button id="boato_adicionar_investimento" type="button" class="btn btn-primary">Adicionar Investimento</button>
                </div>
            </div>
            <div class="row gy-4 mt-2">
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Investimento</th>
                            <th>Valor Aportado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tabela_investimentos">
                        @php
                        $total_investimentos = 0;
                        @endphp
                        @foreach($cota->investimentos as $invest)
                            @php
                            $total_investimentos += $invest->vl_investimento;
                            @endphp
                            <tr id="linha_investimento_cad{{ $invest->id }}">
                                <td>{{ $invest->investimento->user->nome." ".$invest->investimento->titulo." - Retorno: ".dataDbForm($invest->investimento->dt_retorno) }}</td>
                                <td>R$ {{ valorDbForm($invest->vl_investimento) }}</td>
                                <td>
                                    <button onclick="excluir_investimento_cad({{ $invest->id }},{{ $invest->vl_investimento }})" title="Excluir" type="button" class="btn btn-sm rounded-pill btn-icon btn-danger btn-fab demo waves-effect waves-light">
                                        <span class="tf-icons mdi mdi-delete mdi-20px"></span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <td><b>Total</b></td>
                        <td><b id="total_investimentos">R$ {{ valorDbForm($total_investimentos) }}</b></td>
                        <td></td>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <button type="submit" id="botao_salvar" class="btn btn-primary me-2">Salvar</button>
    </div>
</form>
<script>
var total_invest_cad = {{ $total_investimentos }};

window.addEventListener('load',()=>{
    $('.combobox').combobox();
});

function calcula_total(){
    let somatorio = total_invest_cad;
    let variavel = "input.valores";

    inputs = document.querySelectorAll(variavel);
    [].forEach.call(inputs, function(input) {
        if(input.value != ""){
            pag = input.value;
            pag = pag.replaceAll('.','');
            pag = pag.replace(',','.');
            pag = parseFloat(pag);
            somatorio = somatorio + pag;
        }
    });

    somatorio = somatorio.toFixed(2);
    somatorio = somatorio.replace('.',',');

    document.getElementById('total_investimentos').innerHTML = "R$ " + somatorio;
}

document.getElementById('boato_adicionar_investimento').addEventListener('click', ()=>{
    investimento_id = document.getElementById('investimento_id');
    vl_investimento = document.getElementById('vl_investimento');
    if(investimento_id.value != "" && vl_investimento.value != ""){
        option = investimento_id.children[investimento_id.selectedIndex];
        vl_disponivel = parseFloat(option.dataset.valor);
        vl_informado = vl_investimento.value;
        vl_informado = vl_informado.replaceAll('.','');
        vl_informado = parseFloat(vl_informado.replaceAll(',','.'));

        if(vl_informado <= vl_disponivel){
            contador = parseInt(document.getElementById('contador_investimentos').value);
            contador++;
            document.getElementById('contador_investimentos').value = contador;

            tr = document.createElement('tr');
            td1 = document.createElement('td');
            td2 = document.createElement('td');
            td3 = document.createElement('td');
            input1 = document.createElement('input');
            input2 = document.createElement('input');


            tr.setAttribute('id', 'linha_investimento_' + contador);
            td1.innerHTML = option.text;
            td2.innerHTML = 'R$ ' + vl_investimento.value;
            td3.innerHTML = `
            <button onclick="excluir_investimento(${contador})" title="Excluir" type="button" class="btn btn-sm rounded-pill btn-icon btn-danger btn-fab demo waves-effect waves-light">
                <span class="tf-icons mdi mdi-delete mdi-20px"></span>
            </button>
            `;

            input1.setAttribute('type', 'hidden');
            input1.setAttribute('name', 'investimento_id_' + contador);
            input1.setAttribute('value', investimento_id.value);

            input2.setAttribute('type', 'hidden');
            input2.setAttribute('class', 'valores');
            input2.setAttribute('name', 'vl_investimento_' + contador);
            input2.setAttribute('value', vl_investimento.value);


            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);
            tr.appendChild(input1);
            tr.appendChild(input2);

            document.getElementById('tabela_investimentos').appendChild(tr);
            calcula_total();
            investimento_id.value = "";
            vl_investimento.value = "";
        }
        else{
            alert('Valor Informado maior que valor disponivel..');
        }
    }
    else{
        alert('É necessário informar o investimento e o valor.');
    }
})

function excluir_investimento(linha){
    if(confirm('Tem certeza que deseja excluir o investimento da cota?')){
        document.getElementById('linha_investimento_' + linha).remove();
        calcula_total();
    }
}

function excluir_investimento_cad(id, valor){
    if(confirm('Tem certeza que deseja excluir o investimento?')){
        document.getElementById('investimentos_excluir').value = document.getElementById('investimentos_excluir').value + ',' + id;
        document.getElementById('linha_investimento_cad' + id).remove();
        total_invest_cad = total_invest_cad - valor;
        calcula_total();
    }
}

</script>
@endsection
