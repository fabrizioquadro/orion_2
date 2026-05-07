@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-danger mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-account-group"></i>
                Excluir Investidor
            </h4>
        </div>
        <hr>
        <form action="{{ route('adm.investidores.delete') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <div class="row mt-2 gy-4">
                <div class="col-md-12">
                    <p>Tem certeza que deseja excluir o investidor {{ $user->nome }}?</p>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-danger me-2">Excluir</button>
            </div>
        </form>
    </div>
</div>
@endsection
