@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <h4 class="card-title">
            <i class="menu-icon tf-icons mdi mdi-cash-check"></i>
            Relatório Comissões
        </h4>
        <hr>
        <form action="{{ route('adm.relatorios.gerar_comissoes') }}" method="post">
            @csrf
            <div class="row mt-2 gy-4">
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <select id="user_id" name='user_id' class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($investidores as $user)
                                <option value="{{ $user->id }}">{{ $user->nome }}</option>
                            @endforeach
                        </select>
                        <label for="user_id">Investidor:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="date" id="dt_inc" name="dt_inc"/>
                        <label for="dt_inc">Data Início:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="date" id="dt_fn" name="dt_fn"/>
                        <label for="dt_fn">Data Final:</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Gerar</button>
            </div>
        </form>

    </div>
</div>
@endsection
