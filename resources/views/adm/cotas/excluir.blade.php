@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-danger mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-text-box-check-outline"></i>
                Excluir Cota
            </h4>
        </div>
        <hr>
        <form action="{{ route('adm.cotas.delete') }}" method="post">
            @csrf
            <input type="hidden" name="cota_id" value="{{ $cota->id }}">
            <p>Tem certeza que deseja excluir a cota selecionada?</p>
            <button type="submit" class="btn btn-danger me-2">Excluir</button>
        </form>
    </div>
</div>
@endsection
