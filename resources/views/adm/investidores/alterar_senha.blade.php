@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-account-group"></i>
                Alterar Senha Investidor
            </h4>
        </div>
        <hr>
        <form action="{{ route('adm.investidores.update_senha') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <div class="row mt-2 gy-4">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="password" id="password" name="password"/>
                        <label for="password">Nova Senha:</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
