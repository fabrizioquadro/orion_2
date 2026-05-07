@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-hand-coin-outline"></i>
                Investimentos
            </h4>
            <div>
                <a href="{{ route('investidor.investimentos.adicionar') }}" class="btn btn-primary">Novo Investimento</a>
            </div>
        </div>
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
        <div class="table-responsive">
            <table class="tabela-index table" id="table-index">
                <thead class="table-light">
                    <tr>
                        <th>Data Investimento</th>
                        <th>Valor Investimento</th>
                        <th>Título</th>
                        <th>Data Retorno</th>
                        <th>Valor Retorno</th>
                        <th>Situação</th>
                        <th>Confirmado</th>
                    </tr>
                </thead>
                @foreach($investimentos as $investimento)
                    <tr>
                        <td> <span style='display: none'>{{ strtotime($investimento->dt_investimento) }}</span> {{ dataDbForm($investimento->dt_investimento) }}</td>
                        <td>R$ {{ valorDbForm($investimento->vl_investimento) }}</td>
                        <td>{{ $investimento->titulo }}</td>
                        <td>{{ dataDbForm($investimento->dt_retorno) }}</td>
                        <td>R$ {{ valorDbForm($investimento->vl_retorno_ganho) }}</td>
                        <td>{{ $investimento->st_investimento }}</td>
                        <td>{{ $investimento->investimento_cofirmado }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
<script>
window.addEventListener('load',()=>{
  $('#table-index').DataTable({
    order: [[1, 'asc']],
    "language": {
			"sEmptyTable": "Nenhum registro encontrado",
      "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
      "sInfoFiltered": "(Filtrados de _MAX_ registros)",
      "sInfoPostFix": "",
      "sInfoThousands": ".",
      "sLengthMenu": "_MENU_ resultados por página",
      "sLoadingRecords": "Carregando...",
      "sProcessing": "Processando...",
      "sZeroRecords": "Nenhum registro encontrado",
      "sSearch": "Pesquisar",
      "oPaginate": {
        "sNext": "Próximo",
        "sPrevious": "Anterior",
        "sFirst": "Primeiro",
        "sLast": "Último"
      },
      "oAria": {
        "sSortAscending": ": Ordenar colunas de forma ascendente",
        "sSortDescending": ": Ordenar colunas de forma descendente"
      }
    }
  });
})

</script>
@endsection
