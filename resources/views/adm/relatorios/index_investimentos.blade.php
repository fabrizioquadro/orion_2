@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <h4 class="card-title">
            <i class="menu-icon tf-icons mdi mdi-hand-coin-outline"></i>
            Relatório Investimentos
        </h4>
        <hr>
        <form action="{{ route('adm.relatorios.gerar_investimentos') }}" method="post">
            @csrf
            <div class="row mt-2 gy-4">
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <select id="st_investimento" name='st_investimento' class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="Ativo">Ativo</option>
                            <option value="Finalizado">Finalizado</option>
                        </select>
                        <label for="st_investimento">Situação:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <select id="reinvestimento" name='reinvestimento' class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="Sim">Sim</option>
                            <option value="Não">Não</option>
                        </select>
                        <label for="reinvestimento">Reinvestimento:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <select id="investimento_confirmado" name='investimento_confirmado' class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="Sim">Sim</option>
                            <option value="Não">Não</option>
                        </select>
                        <label for="investimento_confirmado">Confirmado:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="date" id="dt_investimento_inc" name="dt_investimento_inc"/>
                        <label for="dt_investimento_inc">Data Início Investimento:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="date" id="dt_investimento_fn" name="dt_investimento_fn"/>
                        <label for="dt_investimento_fn">Data Final Investimento:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="date" id="dt_retorno_inc" name="dt_retorno_inc"/>
                        <label for="dt_retorno_inc">Data Início Retorno:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="date" id="dt_retorno_fn" name="dt_retorno_fn"/>
                        <label for="dt_retorno_fn">Data Final Retorno:</label>
                    </div>
                </div>
                {{--
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <select id="rede" name='rede' class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="Anderson">Anderson</option>
                            <option value="Arismar">Arismar</option>
                        </select>
                        <label for="rede">Rede:</label>
                    </div>
                </div>
                --}}
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Gerar</button>
            </div>
        </form>

    </div>
</div>
@endsection
