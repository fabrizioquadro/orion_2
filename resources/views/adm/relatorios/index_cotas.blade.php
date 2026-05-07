@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <h4 class="card-title">
            <i class="menu-icon tf-icons mdi mdi-text-box-check-outline"></i>
            Relatório Cotas
        </h4>
        <hr>
        <form action="{{ route('adm.relatorios.gerar_cotas') }}" method="post">
            @csrf
            <div class="row mt-2 gy-4">
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="date" id="dt_compra_inc" name="dt_compra_inc"/>
                        <label for="dt_compra_inc">Data Início Compra:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="date" id="dt_compra_fn" name="dt_compra_fn"/>
                        <label for="dt_compra_fn">Data Final Compra:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="date" id="dt_venda_inc" name="dt_venda_inc"/>
                        <label for="dt_venda_inc">Data Início Venda:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="date" id="dt_venda_fn" name="dt_venda_fn"/>
                        <label for="dt_venda_fn">Data Final Venda:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <select id="situacao" name='situacao' class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="Aberta">Aberta</option>
                            <option value="Finalizada">Finalizada</option>
                        </select>
                        <label for="situacao">Situação:</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Gerar</button>
            </div>
        </form>

    </div>
</div>
@endsection
