@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <h4 class="card-title">
            <i class="menu-icon tf-icons mdi mdi-text-box-check-outline"></i>
            Relatório Cotas
        </h4>
        <hr>
        <h6 class="card-title">Filtros</h6>
        <div class="row mt-2 gy-4">
            <div class="col-md-3 form-group">
                <label for="user_id">Data Compra Início:</label><br>
                <b>{{ dataDbform($dados_pesquisa['dt_compra_inc']) }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="user_id">Data Compra Final:</label><br>
                <b>{{ dataDbform($dados_pesquisa['dt_compra_fn']) }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="user_id">Data Venda Início:</label><br>
                <b>{{ dataDbform($dados_pesquisa['dt_venda_inc']) }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="user_id">Data Venda Final:</label><br>
                <b>{{ dataDbform($dados_pesquisa['dt_venda_fn']) }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="user_id">Situação:</label><br>
                <b>{{ $dados_pesquisa['situacao'] }}</b>
            </div>
        </div>
        <hr>
        <div class="table-responsive mt-3">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>Cota</th>
                        <th>Situação</th>
                        <th>Data Compra</th>
                        <th>Data Venda</th>
                        <th>Dias (Venda)</th>
                        <th>Compra (R$)</th>
                        <th>Venda (R$)</th>
                        <th>Lucro Bruto</th>
                        <th>Lucro Ratiado</th>
                        <th>Comissão</th>
                        <th>Lucro Liquido</th>
                        <th>Investimento<br>Comissão(%)</th>
                        <th>Investimento(%)</th>
                        <th>% ao mês</th>
                        <th>Carta</th>
                        <th>Parcelas</th>
                        <th>Valor Parcela</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($cotas) == 0)
                        <tr>
                            <td colspan="4">Não foi encontrada nenhuma cota</td>
                        </tr>
                    @endif
                    @foreach($cotas as $cota)
                        <tr>
                            <td>{{ $cota->codigo }}</td>
                            <td>{{ $cota->situacao }}</td>
                            <td>{{ dataDbForm($cota->dt_compra) }}</td>
                            <td>{{ dataDbForm($cota->dt_venda) }}</td>
                            <td>{{ $cota->dias_venda }}</td>
                            <td>R$ {{ valorDbForm($cota->vl_compra) }}</td>
                            <td>R$ {{ valorDbForm($cota->vl_venda) }}</td>
                            <td>R$ {{ valorDbForm($cota->lucro_bruto) }}</td>
                            <td>R$ {{ valorDbForm($cota->lucro_ratiado) }}</td>
                            <td>R$ {{ valorDbForm($cota->comissao) }}</td>
                            <td>R$ {{ valorDbForm($cota->lucro_liquido) }}</td>
                            <td>{{ round($cota->lucro_ratiado / $cota->vl_compra * 100, 2) }}%</td>
                            <td>{{ round($cota->lucro_liquido / $cota->vl_compra * 100, 2) }}%</td>
                            <td>{{ round(($cota->lucro_ratiado / $cota->vl_compra * 100) / $cota->dias_venda * 30, 2) }}%</td>
                            <td>R$ {{ valorDbForm($cota->vl_carta) }}</td>
                            <td>{{ $cota->parcelas }}</td>
                            <td>R$ {{ valorDbForm($cota->vl_parcela) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
