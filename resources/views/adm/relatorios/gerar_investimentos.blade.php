@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <h4 class="card-title">
            <i class="menu-icon tf-icons mdi mdi-hand-coin-outline"></i>
            Relatório Investimentos
        </h4>
        <hr>
        <div class="row mt-2 gy-4">
            <div class="col-md-3 form-group">
                <label for="user_id">Investidor:</label><br>
                <b>{{ $user ? $user->nome : '' }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="st_investimento">Situação:</label><br>
                <b>{{ $dados_pesquisa['st_investimento'] }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="reinvestimento">Reinvestimento:</label><br>
                <b>{{ $dados_pesquisa['reinvestimento'] }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="investimento_confirmado">Confirmado:</label><br>
                <b>{{ $dados_pesquisa['investimento_confirmado'] }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="dt_investimento_inc">Data Início Investimento:</label><br>
                <b>{{ dataDbForm($dados_pesquisa['dt_investimento_inc']) }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="dt_investimento_fn">Data Final Investimento:</label><br>
                <b>{{ dataDbForm($dados_pesquisa['dt_investimento_fn']) }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="dt_retorno_inc">Data Início Retorno:</label><br>
                <b>{{ dataDbForm($dados_pesquisa['dt_retorno_inc']) }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="dt_retorno_fn">Data Final Retorno:</label><br>
                <b>{{ dataDbForm($dados_pesquisa['dt_retorno_fn']) }}</b>
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>Data Investimento</th>
                        <th>Investidor</th>
                        <th>Valor Investimento</th>
                        <th>Título</th>
                        <th>Data Retorno</th>
                        <th>Valor Retorno</th>
                        <th>Situação</th>
                        <th>Confirmado</th>
                        <th>Cotas Investidas</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($investimentos) == 0)
                    <tr>
                        <td colspan="9">Não foi encontrada nenhum investimento.</td>
                    </tr>
                    @endif
                    @foreach($investimentos as $investimento)
                        <tr>
                            <td> <span style='display: none'>{{ strtotime($investimento->dt_investimento) }}</span> {{ dataDbForm($investimento->dt_investimento) }}</td>
                            <td>{{ App\Models\User::where('id', $investimento->user_id)->first()->nome }}</td>
                            <td>R$ {{ valorDbForm($investimento->vl_investimento) }}</td>
                            <td>{{ $investimento->titulo }}</td>
                            <td>{{ dataDbForm($investimento->dt_retorno) }}</td>
                            <td>R$ {{ valorDbForm($investimento->vl_retorno_ganho) }}</td>
                            <td>{{ $investimento->st_investimento }}</td>
                            <td>{{ $investimento->investimento_cofirmado }}</td>
                            <td>{!! App\Models\Investimento::where('id', $investimento->id)->first()->get_cotas_investimento() !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>
@endsection
