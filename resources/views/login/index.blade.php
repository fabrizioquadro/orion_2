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
                    @if($mensagem = Session::get('mensagem'))
                        <div class="alert alert-solid-success" role="alert">
                            {{ $mensagem }}
                        </div>
                    @endif
                    <h5 class="mb-3 colorAmarelo">Login</h5>
                    <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="email" class="colorBranco">Email:</label>
                            <input class="form-control form-control-rounded" required name="email" type="email">
                        </div>
                        <div class="form-group">
                            <label for="password" class="colorBranco">Senha:</label>
                            <input class="form-control form-control-rounded" required name="password" type="password">
                        </div>
                        <button class="btn btn-rounded btn-primary btn-block mt-2">Login</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a class="text-muted colorBranco" href="{{ route('cadastrar') }}">
                            <u>Cadastre-se!</u>
                        </a>
                    </div>
                    <div class="mt-3 text-center">
                        <a class="text-muted colorBranco" href="{{ route('esqueceu_senha') }}">
                            <u>Esqueceu a Senha?</u>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
