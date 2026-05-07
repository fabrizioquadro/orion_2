@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <h4 class="card-title">
            <i class="menu-icon tf-icons mdi mdi-cash-minus"></i>
            Relatório Saques
        </h4>
        <hr>
        <h6 class="card-title">Filtros</h6>
        <div class="row mt-2 gy-4">
            <div class="col-md-4 form-group">
                <label for="user_id">Investidor:</label><br>
                <b>{{ $user ? $user->nome : '' }}</b>
            </div>
            <div class="col-md-4 form-group">
                <label for="dt_inc">Data Início:</label><br>
                <b>{{ dataDbForm($dados_pesquisa['dt_inc']) }}</b>
            </div>
            <div class="col-md-4 form-group">
                <label for="dt_fn">Data Final:</label><br>
                <b>{{ dataDbForm($dados_pesquisa['dt_fn']) }}</b>
            </div>
        </div>
        <hr>
        <div class="table-responsive mt-3">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>Data</th>
                        <th>Investidor</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($saques) == 0)
                        <tr>
                            <td colspan="4">Não foi encontrada nenhum saque</td>
                        </tr>
                    @endif
                    @foreach($saques as $saque)
                        @php
                        $somatorio += $saque->vl_resgate;
                        @endphp
                        <tr>
                            <td>{{ dataDbForm($saque->dt_resgate) }}</td>
                            <td>{{ App\Models\User::where('id', $saque->user_id)->first()->nome }}</td>
                            <td>{{ $saque->titulo }}</td>
                            <td>R$ {{ valorDbForm($saque->vl_resgate) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><b>TOTAL SAQUES</b></td>
                        <td><b>R$ {{ valorDbForm($somatorio) }}</b></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
