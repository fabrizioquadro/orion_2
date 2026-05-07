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
                <a href="{{ route('adm.investimentos.adicionar') }}" class="btn btn-primary">Adicionar</a>
                <a href="{{ route('adm.investimentos.analise_situacao') }}" class="btn btn-secondary">Script Análise</a>
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
                        <th></th>
                        <th>Data Investimento</th>
                        <th>Investidor</th>
                        <th>Título</th>
                        <th>Valor Investimento</th>
                        <th>Indice</th>
                        <th>Data Retorno</th>
                        <th>Valor Retorno</th>
                        <th>Situação</th>
                        <th>Confirmado</th>
                        {{-- <th>Contrato Assinado</th> --}}
                    </tr>
                </thead>
                @foreach($investimentos as $investimento)
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow show" data-bs-toggle="dropdown" aria-expanded="true">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" data-popper-placement="bottom-end">
                                    <a class="dropdown-item waves-effect" href="{{ route('adm.investimentos.editar', $investimento->id) }}"><i class="mdi mdi-pencil-outline me-1"></i> Editar</a>
                                    <a class="dropdown-item waves-effect" href="{{ route('adm.investimentos.excluir', $investimento->id) }}"><i class="mdi mdi-trash-can-outline me-1"></i> Excluir</a>
                                    @if($investimento->st_investimento != "Finalizado")
                                        <a class="dropdown-item waves-effect" href="{{ route('adm.investimentos.finalizar', $investimento->id) }}"><i class="mdi mdi-check-circle-outline me-1"></i> Finalizar</a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td> <span style='display: none'>{{ strtotime($investimento->dt_investimento) }}</span> {{ dataDbForm($investimento->dt_investimento) }}</td>
                        <td>{{ $investimento->user->nome }}</td>
                        <td>{{ $investimento->titulo }}</td>
                        <td>R$ {{ valorDbForm($investimento->vl_investimento) }}</td>
                        <td>{{ $investimento->indice_rendimento }}%</td>
                        <td>{{ dataDbForm($investimento->dt_retorno) }}</td>
                        <td>R$ {{ valorDbForm($investimento->vl_retorno_ganho) }}</td>
                        <td>{{ $investimento->st_investimento }}</td>
                        <td>{{ $investimento->investimento_cofirmado }}</td>
                        {{--<td>{{ App\Http\Controllers\ApiZapSignController::verifica_assinatura($investimento) }}</td>--}}
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
