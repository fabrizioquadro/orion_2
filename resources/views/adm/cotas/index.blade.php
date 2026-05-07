@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-text-box-check-outline"></i>
                Cotas
            </h4>
            <a href="{{ route('adm.cotas.adicionar') }}" class="btn btn-primary">Adicionar</a>
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
                        <th>Cota</th>
                        <th>Situação</th>
                        <th>Data Compra</th>
                        <th>Data Venda</th>
                        <th>Dias (Venda)</th>
                        <th>Compra (R$)</th>
                        <th>Venda (R$)</th>
                        <th>Lucro Bruto</th>
                        <th>Lucro Ratiado</th>
                        <th>Comissão</th>
                        <th>Lucro Liquido</th>
                        <th>Carta</th>
                        <th>Parcelas</th>
                        <th>Valor Parcela</th>
                    </tr>
                </thead>
                @foreach($cotas as $cota)
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow show" data-bs-toggle="dropdown" aria-expanded="true">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" data-popper-placement="bottom-end">
                                    <a class="dropdown-item waves-effect" href="{{ route('adm.cotas.acessar', $cota->id) }}"><i class="mdi mdi-file-outline me-1"></i> Acessar</a>
                                    @if($cota->situacao == "Aberta")
                                        <a class="dropdown-item waves-effect" href="{{ route('adm.cotas.excluir', $cota->id) }}"><i class="mdi mdi-delete-outline me-1"></i> Excluir</a>
                                        <a class="dropdown-item waves-effect" href="{{ route('adm.cotas.editar', $cota->id) }}"><i class="mdi mdi-pencil me-1"></i> Editar</a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $cota->codigo }}</td>
                        <td>{{ $cota->situacao }}</td>
                        <td>{{ dataDbForm($cota->dt_compra) }}</td>
                        <td>{{ dataDbForm($cota->dt_venda) }}</td>
                        <td>{{ $cota->dias_venda }}</td>
                        <td>R$ {{ valorDbForm($cota->vl_compra) }}</td>
                        <td>R$ {{ valorDbForm($cota->vl_venda) }}</td>
                        <td>R$ {{ valorDbForm($cota->lucro_bruto) }}</td>
                        <td>R$ {{ valorDbForm($cota->lucro_ratiado) }}</td>
                        <td>R$ {{ valorDbForm($cota->comissao) }}</td>
                        <td>R$ {{ valorDbForm($cota->lucro_liquido) }}</td>
                        <td>R$ {{ valorDbForm($cota->vl_carta) }}</td>
                        <td>{{ $cota->parcelas }}</td>
                        <td>R$ {{ valorDbForm($cota->vl_parcela) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
<script>
window.addEventListener('load',()=>{
  $('#table-index').DataTable({
    order: [[1, 'desc']],
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
