@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-account-group"></i>
                Arquivos {{ $user->nome }}
            </h4>
        </div>
        <hr>
        <form action="{{ route('adm.investidores.arquivos_update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="file" multiple id="arquivos" name="arquivos[]"/>
                        <label for="arquivos">Adicionar Arquivos:</label>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <button type="submit" class="btn btn-primary me-2">Salvar</button>
                </div>
            </div>
        </form>
        <hr>
        <div class="row">
            <div class="col-12 col-md-6 mb-4 mb-xl-0">
                <small class="text-light fw-medium">Arquivos Cadastrados</small>
                <div class="demo-inline-spacing mt-3">
                    <div class="list-group">
                        @foreach($user->arquivos as $arquivo)
                            <div class="list-group-item list-group-item-action d-flex align-items-center waves-effect" style='cursor: default !important'>
                                <div class="w-100">
                                    <div class="d-flex justify-content-between">
                                        <div class="user-info">
                                            <a target="_blank" href="/public/investidores/{{ $user->id }}/arquivos/{{ $arquivo->arquivo }}">
                                                <h6 class="mt-2 mb-0">{{ $arquivo->nome }}</h6>
                                            </a>
                                        </div>
                                        <div class="add-btn">
                                            <button onclick="excluir_arquivo({{ $arquivo->id }})" class="btn btn-danger btn-sm waves-effect waves-light">Excluir</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function excluir_arquivo(arquivo_id){
    if(confirm('Tem certeza que deseja excluir o arquivo?')){
        window.location.href = "/adm/investidores/arquivos_delete/" + arquivo_id;
    }
}
</script>
@endsection
