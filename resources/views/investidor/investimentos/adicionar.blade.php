@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-hand-coin-outline"></i>
                Novo Investimento
            </h4>
        </div>
        <hr>
        <form action="{{ route('investidor.investimentos.insert') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row mt-2 gy-4">
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="date" id="dt_investimento" name="dt_investimento"/>
                        <label for="dt_investimento">Data Investimento:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="vl_investimento" name="vl_investimento" onkeypress="return(MascaraMoeda(this,'.',',',event))"/>
                        <label for="vl_investimento">Valor Investimento:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="file" id="comprovante" name="comprovante"/>
                        <label for="comprovante">Comprovante:</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="titulo" name="titulo"/>
                        <label for="titulo">Título:</label>
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
