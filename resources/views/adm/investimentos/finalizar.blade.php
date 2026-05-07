@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-hand-coin-outline"></i>
                Finalizar Investimento
            </h4>
        </div>
        <hr>
        <form action="{{ route('adm.investimentos.finalizar_set') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="investimento_id" value="{{ $investimento->id }}">
            <div class="row mt-2 gy-4">
                <div class="col-md-12">
                    <p>Tem certeza que deseja finalizar o investimento de {{ $investimento->user->nome }}?</p>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Finalizar</button>
            </div>
        </form>
    </div>
</div>
@endsection
