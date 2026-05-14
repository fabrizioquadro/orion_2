@extends('layout.sistema')

@section('conteudo')
<style>
    .v2-dashboard .card-title-header {
        font-weight: 600;
        color: #566a7f;
        margin-bottom: 0.5rem;
    }
    .v2-dashboard .stat-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .v2-dashboard .stat-card:hover {
        transform: translateY(-5px);
    }
    .v2-dashboard .avatar-stat {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }
    .v2-dashboard .cota-card {
        border: 1px solid #edf2f9;
        border-radius: 15px;
        background: #fff;
    }
    .v2-dashboard .cota-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .v2-dashboard .cota-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 8px;
    }
    .v2-dashboard .cota-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 6px;
    }
    .v2-dashboard .progress {
        height: 8px;
        border-radius: 10px;
        background-color: #f0f2f5;
    }
    .v2-dashboard .progress-bar {
        border-radius: 10px;
    }
    .v2-dashboard .chart-container {
        min-height: 300px;
    }
    .text-success-custom { color: #2ecc71 !important; }
    .text-warning-custom { color: #f1c40f !important; }
    .text-info-custom { color: #3498db !important; }
    .bg-success-light { background-color: rgba(46, 204, 113, 0.1) !important; }
    .bg-warning-light { background-color: rgba(241, 196, 15, 0.1) !important; }
    .bg-info-light { background-color: rgba(52, 152, 219, 0.1) !important; }
    .bg-danger-light { background-color: rgba(231, 76, 60, 0.1) !important; }
</style>

<div class="v2-dashboard">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="mb-0">Dashboard</h3>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm w-auto">
                <option>Todas</option>
                <option>Ativas</option>
                <option>Finalizadas</option>
            </select>
            <select class="form-select form-select-sm w-auto">
                <option>30 dias</option>
                <option>90 dias</option>
                <option>1 ano</option>
            </select>
        </div>
    </div>

    <!-- Top Cards -->
    <div class="row g-4 mb-4">
        <!-- Patrimônio Total -->
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-stat bg-warning-light me-3">
                            <i class="mdi mdi-wallet-outline mdi-24px text-warning"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Patrimônio Total</p>
                            <h4 class="mb-0">R$ {{ valorDbForm($patrimonio_total) }}</h4>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="text-success-custom small fw-semibold">
                            <i class="mdi mdi-circle mdi-14px me-1"></i> Atualizado agora
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Lucro Total -->
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-stat bg-success-light me-3">
                            <i class="mdi mdi-trending-up mdi-24px text-success"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Lucro Total</p>
                            <h4 class="mb-0 text-success-custom">R$ {{ valorDbForm($total_lucro) }}</h4>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="text-success-custom small fw-semibold">
                            + {{ $porcentagem_lucro_total }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Capital Investido -->
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-stat bg-info-light me-3">
                            <i class="mdi mdi-bank-outline mdi-24px text-info"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Capital Investido</p>
                            <h4 class="mb-0">R$ {{ valorDbForm($total_capital_investido) }}</h4>
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-muted small">
                        {{ count($dados_cotas) }} investimentos ativos
                    </div>
                </div>
            </div>
        </div>
        <!-- Próximo Vencimento -->
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-stat bg-danger-light me-3">
                            <i class="mdi mdi-timer-sand mdi-24px text-danger"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Próximo Vencimento</p>
                            <h4 class="mb-0">{{ $proxima_cota ? 'Cota ' . $proxima_cota['codigo'] : '---' }}</h4>
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-muted small">
                        {{ $proxima_cota ? $proxima_cota['dias_restantes'] . ' dias' : '---' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="card-title mb-0">Evolução do Patrimônio <i class="mdi mdi-help-circle-outline text-muted small"></i></h5>
                    </div>
                    <div id="patrimonioChart" class="chart-container"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cotas Section -->
    <div class="row g-4 mb-4">
        @foreach($dados_cotas as $cota)
        <div class="col-md-6 col-lg-3">
            <div class="card cota-card h-100">
                <div class="card-body">
                    <div class="cota-header">
                        <div class="d-flex align-items-center">
                            <span class="cota-dot {{ $cota['situacao'] == 'Aberta' ? 'bg-success' : 'bg-secondary' }}"></span>
                            <h6 class="mb-0">Cota {{ $cota['codigo'] }}</h6>
                        </div>
                        @if($cota['situacao'] == 'Aberta' && $cota['dias_restantes'] <= 15)
                            <span class="cota-badge bg-warning-light text-warning">Vence em {{ $cota['dias_restantes'] }} dias</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-muted small mb-0">Valor investido:</p>
                        <p class="fw-bold mb-2">R$ {{ valorDbForm($cota['valor_investido']) }}</p>
                        
                        <p class="text-muted small mb-0">Lucro:</p>
                        <p class="text-success-custom fw-bold mb-3">R$ {{ valorDbForm($cota['lucro']) }}</p>
                    </div>

                    <div class="mb-1">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>{{ $cota['situacao'] == 'Finalizada' ? 'Finalizada' : 'Prazo expirar' }}</span>
                            <span class="fw-bold">{{ $cota['porcentagem_prazo'] }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $cota['porcentagem_prazo'] }}%" 
                                 aria-valuenow="{{ $cota['porcentagem_prazo'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    @if($cota['situacao'] == 'Aberta')
                    <div class="mt-2 text-center">
                        <small class="text-muted">Vence em <strong>{{ $cota['dias_restantes'] }} dias</strong></small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Bottom Footer -->
    <div class="card stat-card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <p class="text-muted small mb-1">Rentabilidade média</p>
                    <h5 class="mb-0">{{ $rentabilidade_media }}%</h5>
                </div>
                <div class="col-md-3 border-start">
                    <p class="text-muted small mb-1">Prazo médio restante</p>
                    <h5 class="mb-0">{{ $prazo_medio_restante }} dias</h5>
                </div>
                <div class="col-md-3 border-start">
                    <p class="text-muted small mb-1">Total de cotas</p>
                    <h5 class="mb-0">{{ count($dados_cotas) }}</h5>
                </div>
                <div class="col-md-3 text-end">
                    <button class="btn btn-outline-success px-4 rounded-pill">Ver todas</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var options = {
        series: [{
            name: 'Patrimônio',
            data: {!! json_encode($grafico_patrimonio['valores']) !!}
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3,
            colors: ['#2ecc71']
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: {!! json_encode($grafico_patrimonio['labels']) !!},
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            }
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return "R$ " + value.toLocaleString('pt-BR');
                }
            }
        },
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 4
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm'
            },
            y: {
                formatter: function (value) {
                    return "R$ " + value.toLocaleString('pt-BR');
                }
            }
        },
        colors: ['#2ecc71']
    };

    var chart = new ApexCharts(document.querySelector("#patrimonioChart"), options);
    chart.render();
});
</script>

@endsection
