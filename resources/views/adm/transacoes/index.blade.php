@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <h4 class="card-title">
            <i class="menu-icon tf-icons mdi mdi-swap-horizontal"></i>
            Transações
        </h4>
        @if($mensagem = Session::get('mensagem'))
            <div class="alert alert-success alert-dismissible mt-3" role="alert">
                {{ $mensagem }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($mensagem = Session::get('mensagem_erro'))
            <div class="alert alert-danger alert-dismissible mt-3" role="alert">
                {{ $mensagem }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <hr>
        <form action="{{ route('adm.transacoes.gerar') }}" method="post">
            @csrf
            <div class="row align-items-end mt-2 gy-4">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <select required id="user_id" name='user_id' class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->nome }}</option>
                            @endforeach
                        </select>
                        <label for="user_id">Investidor:</label>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <button type="submit" class="btn btn-primary me-2">Gerar</button>
                </div>
            </div>
        </form>


    </div>
</div>
@endsection
