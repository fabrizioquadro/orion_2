@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <h4 class="card-title">
            <i class="menu-icon tf-icons mdi mdi-finance"></i>
            Dashboard
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
        <h5 class="card-title mt-1">Investimentos Pendentes de Confirmação</h5>
        <div class="table-responsive mt-3">
            <table class="tabela-index table" id="table-index_invest">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th>Data Investimento</th>
                        <th>Comprovante</th>
                        <th>Investidor</th>
                        <th>Valor Investimento</th>
                        <th>Título</th>
                        <th>Data Retorno</th>
                        <th>Valor Retorno</th>
                        <th>Situação</th>
                        <th>Reinveste</th>
                        <th>Confirmado</th>
                    </tr>
                </thead>
                @foreach($investimentos as $investimento)
                    <tr>
                        <td> <a href="{{ route('adm.dashboard.confirma_investimento', $investimento->id) }}" class="btn btn-sm btn-primary">Confirmar</a></td>
                        <td> <span style='display: none'>{{ strtotime($investimento->dt_investimento) }}</span> {{ dataDbForm($investimento->dt_investimento) }}</td>
                        <td>
                            @if($investimento->comprovante)
                                <a target='_blank' href="/public/comprovante_investimentos/{{ $investimento->comprovante }}" class="btn btn-sm btn-label-secondary waves-effect">Comprovante</a>
                            @endif
                        </td>
                        <td>{{ $investimento->user->nome }}</td>
                        <td>R$ {{ valorDbForm($investimento->vl_investimento) }}</td>
                        <td>{{ $investimento->titulo }}</td>
                        <td>{{ dataDbForm($investimento->dt_retorno) }}</td>
                        <td>R$ {{ valorDbForm($investimento->vl_retorno_ganho) }}</td>
                        <td>{{ $investimento->st_investimento }}</td>
                        <td>{{ $investimento->reinvestimento }}</td>
                        <td>{{ $investimento->investimento_cofirmado }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <hr>
        <h5 class="card-title mt-5">Resgates Pendentes de Confirmação</h5>
        <div class="table-responsive mt-3">
            <table class="tabela-index table" id="table-index_resgate">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th>Data Resgate</th>
                        <th>Investidor</th>
                        <th>Título</th>
                        <th>Valor Resgate</th>
                        <th>Confirmado</th>
                    </tr>
                </thead>
                @foreach($resgates as $resgate)
                    <tr>
                        <td> <a href="{{ route('adm.dashboard.confirma_resgate', $resgate->id) }}" class="btn btn-sm btn-primary">Confirmar</a></td>
                        <td> <span style='display: none'>{{ strtotime($resgate->dt_resgate) }}</span> {{ dataDbForm($resgate->dt_resgate) }}</td>
                        <td>{{ $resgate->user->nome }}</td>
                        <td>{{ $resgate->titulo }}</td>
                        <td>R$ {{ valorDbForm($resgate->vl_resgate) }}</td>
                        <td>{{ $resgate->resgate_cofirmado }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

    </div>
</div>
<script type="text/javascript">
window.addEventListener('load',()=>{
  $('#table-index_invest').DataTable({
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
  $('#table-index_resgate').DataTable({
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
