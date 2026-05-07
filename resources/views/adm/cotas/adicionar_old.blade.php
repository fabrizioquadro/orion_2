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
<form id="formulario" action="{{ route('adm.cotas.insert') }}" method="post">
    @csrf
    <input type="hidden" name="contador_investimentos" id="contador_investimentos" value="1">
    <div class="card card-border-shadow-primary mb-4">
        <div class="card-body">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-text-box-check-outline"></i>
                Adicionar Cota
            </h4>
            <hr>
            <div class="row gy-4 mt-2">
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="codigo" name="codigo"/>
                        <label for="codigo">Código:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="vl_carta" name="vl_carta" onkeypress="return(MascaraMoeda(this,'.',',',event))"/>
                        <label for="vl_carta">Valor da Carta:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="number" id="parcelas" name="parcelas"/>
                        <label for="parcelas">Número de Parcelas:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="vl_parcela" name="vl_parcela" onkeypress="return(MascaraMoeda(this,'.',',',event))"/>
                        <label for="vl_parcela">Valor da Parcela:</label>
                    </div>
                </div>
            </div>
            <div class="row gy-4 mt-2">
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="date" id="dt_compra" name="dt_compra"/>
                        <label for="dt_compra">Data da Compra:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="vl_compra" name="vl_compra" onkeypress="return(MascaraMoeda(this,'.',',',event))"/>
                        <label for="vl_compra">Valor da Compra:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="date" id="dt_venda" name="dt_venda"/>
                        <label for="dt_venda">Data da Venda:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="vl_venda" name="vl_venda" onkeypress="return(MascaraMoeda(this,'.',',',event))"/>
                        <label for="vl_venda">Valor da Venda:</label>
                    </div>
                </div>
            </div>
            <div class="row gy-4 mt-2">
                <div class="col-md-12">
                    <div class="form-floating form-floating-outline mb-4">
                        <textarea class="form-control h-px-100" id="obs" name="obs"></textarea>
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
                <button id="boato_adicionar_investimento" type="button" class="btn btn-sm btn-primary">Adicionar Investimento</button>
            </div>
            <hr>
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
                        <tr id="linha_investimento_1">
                            <td>
                                <select name="investimento_id_1" id="investimento_id_1" class="form-control">
                                    <option value="">Options</option>
                                    @foreach($investimentos as $investimento)
                                        <option value="{{ $investimento->id }}">{{ $investimento->user->nome." ".$investimento->titulo." - Retorno: ".dataDbForm($investimento->dt_retorno)." - Valor Disponível: R$".valorDbForm($investimento->vl_cota_restante) }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input class="form-control valores" onblur="calcula_total()" type="text" id="investimento_vl_1" name="investimento_vl_1" onkeypress="return(MascaraMoeda(this,'.',',',event))"/></td>
                            <td>
                                <button onclick="excluir_investimento(1)" title="Excluir" type="button" class="btn btn-sm rounded-pill btn-icon btn-danger btn-fab demo waves-effect waves-light">
                                    <span class="tf-icons mdi mdi-delete mdi-20px"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <td><b>Total</b></td>
                        <td><b id="total_investimentos">R$ 0,00</b></td>
                        <td></td>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <button type="button" id="botao_salvar" class="btn btn-primary me-2">Salvar</button>
    </div>
</form>
<script>
function calcula_total(){
    let somatorio = 0;
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
    contador = parseInt(document.getElementById('contador_investimentos').value);
    contador++;
    document.getElementById('contador_investimentos').value = contador;

    tr = document.createElement('tr');

    tr.innerHTML = `
    <td>
        <select name="investimento_id_${contador}" id="investimento_id_${contador}" class="form-control">
            <option value="">Options</option>
            @foreach($investimentos as $investimento)
                <option value="{{ $investimento->id }}">{{ $investimento->user->nome." ".$investimento->titulo." - Retorno: ".dataDbForm($investimento->dt_retorno)." - Valor Disponível: R$".valorDbForm($investimento->vl_cota_restante) }}</option>
            @endforeach
        </select>
    </td>
    <td><input class="form-control valores" onblur="calcula_total()" type="text" id="investimento_vl_${contador}" name="investimento_vl_${contador}" onkeypress="return(MascaraMoeda(this,'.',',',event))"/></td>
    `;

    document.getElementById('tabela_investimentos').appendChild(tr);

})

document.getElementById('botao_salvar').addEventListener('click', ()=>{
    vl_carta = document.getElementById('vl_carta').value;
    parcelas = document.getElementById('parcelas').value;
    vl_parcela = document.getElementById('vl_parcela').value;
    dt_compra = document.getElementById('dt_compra').value;
    vl_compra = document.getElementById('vl_compra').value;
    dt_venda = document.getElementById('dt_venda').value;
    vl_venda = document.getElementById('vl_venda').value;

    if(vl_carta != "" && parcelas != "" && vl_parcela != "" && dt_compra != "" && vl_compra != "" && dt_venda != "" && vl_venda != ""){
        //vamos conferir os valores se batem com a compra
        somador = 0;
        for(i=1 ; i<= parseInt(document.getElementById('contador_investimentos').value) ; i++){
            id = document.getElementById('investimento_id_' + i).value;
            valor = document.getElementById('investimento_vl_' + i).value;

            if(id != "" && valor != ""){
                valor = valor.replaceAll('.','');
                valor = parseFloat(valor.replace(',','.'));
                somador += valor;
            }
        }
        variavel = vl_compra.replaceAll('.','');
        variavel = parseFloat(variavel.replace(',','.'));

        if(variavel == somador || somador == 0){
            document.getElementById('formulario').submit();
        }
        else{
            alert('Valor de compra e valores de investimentos alocados não confere.');
        }
    }
    else{
        alert('É necessário preencher todos os campos!');
    }
})
</script>
@endsection
