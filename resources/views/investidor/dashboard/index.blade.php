@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <h4 class="card-title">
            <i class="menu-icon tf-icons mdi mdi-finance"></i>
            Dashboard
        </h4>
        <hr>
        <div class="row mt-2 gy-4">
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-warning h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-warning"><i class="mdi mdi-cash mdi-24px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">R$ {{ valorDbForm($total_investido) }}</h4>
                        </div>
                        <p class="mb-0 text-heading">Total Aportado</p>
                        <p class="mb-0">

                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-success"><i class="mdi mdi-cash-plus mdi-24px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">R$ {{ valorDbForm($total_ganho) }}</h4>
                        </div>
                        <p class="mb-0 text-heading">Total Ganho</p>
                        <p class="mb-0">
                            <span class="me-1">+ {{ $porcentagem_ganho }}%</span>
                            <small class="text-muted">Totos os valores.</small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-secondary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-secondary"><i class="mdi mdi-swap-horizontal mdi-24px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">{{ $investimentos->count() }}</h4>
                        </div>
                        <p class="mb-0 text-heading">Investimento Ativo</p>
                        <p class="mb-0">

                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-danger h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-danger"><i class="mdi mdi-calendar mdi-24px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">{{ $invest_proximo ? dataDbForm($invest_proximo->dt_retorno) : '__/__/____' }}</h4>
                        </div>
                        <p class="mb-0 text-heading">Próximo Venc.</p>
                        <p class="mb-0">

                        </p>
                    </div>
                </div>
            </div>
        </div>
        <h6 class="card-title mt-3">Investimentos Ativos</h6>
        @if($investimentos->count() == 0)
            <span>Nenhum Investimento Ativo</span>
        @endif
        <div class="row mt-3 gy-4">
            @foreach($investimentos as $investimento)
                @php
                $numero = strtotime($investimento->dt_retorno) - strtotime(date('Y-m-d'));
                $dias_restantes = $numero / 86400;
                @endphp
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $investimento->titulo }}</h5>
                            <div class="card-subtitle mb-3">Valor Investido: <br> <strong>R$ {{ valorDbForm($investimento->vl_investimento) }}</strong> </div>
                            <p class="card-text">
                                Valor Ganho:<br><strong>R$ {{ valorDbForm($investimento->vl_retorno_ganho) }}</strong><br>
                                Prazo Restante:<br><strong>{{ $dias_restantes }} dias</strong>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>



    </div>
</div>
@endsection
