@extends('layout/login')

@section('conteudo')
<div class="row">
    <div class="col-md-12">
        <div class="p-4">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    @if($mensagem = Session::get('erro'))
                        <div class="alert alert-solid-danger" role="alert">
                            {{ $mensagem }}
                        </div>
                    @endif
                    <h5 class="mb-3 colorAmarelo">Recuperar Senha</h5>
                    <form id="formAuthentication" class="mb-3" action="{{ route('recuperar_senha') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="email" class="colorBranco">Email:</label>
                            <input class="form-control form-control-rounded" required name="email" type="email">
                        </div>
                        <button class="btn btn-rounded btn-primary btn-block mt-2">Recuperar Senha</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
