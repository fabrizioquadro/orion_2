@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <h4 class="card-title">
            <i class="menu-icon tf-icons mdi mdi-cog-outline"></i>
            Perfil
        </h4>
        @if($mensagem = Session::get('mensagem'))
        <div class="alert alert-success" role="alert">
            {{ $mensagem }}
        </div>
        @endif
        @if($mensagem = Session::get('mensagem_erro'))
        <div class="alert alert-danger" role="alert">
            {{ $mensagem }}
        </div>
        @endif
        <hr>
        <div class="row">
            <div class="col-md-8">
                <div class="card card-border-shadow-primary mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Informações Pessoais</h5>
                        <span>Atualize suas informações de contato</span>
                        <hr>
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            @if($user->imagem)
                                <img src="{{ asset('/public/img/usuarios/'.$user->imagem.'?'.date('ymdhis')) }}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="uploadedAvatar"/>
                            @else
                                <div class="avatar avatar-xl me-2 w-px-120 h-px-120">
                                    <span style="height:100px !important; width: 100px !important" class="avatar-initial rounded-circle bg-label-secondary">{{ substr($user->nome,0,2) }}</span>
                                </div>
                            @endif
                            <form id="form_foto" action="{{ route('perfil.atualizar_foto') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                                        <span class="d-none d-sm-block">Atualizar Foto</span>
                                        <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                                        <input required onchange="document.getElementById('form_foto').submit()" type="file" name="imagem" id="upload" class="account-file-input" hidden accept="image/png, image/jpeg" />
                                    </label>
                                    <button id='resetar_foto_perfil' type="button" class="btn btn-outline-danger account-image-reset mb-3">
                                        <i class="mdi mdi-reload d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Reset</span>
                                    </button>
                                    <div class="small">Allowed JPG, GIF or PNG. Max size of 800K</div>
                                </div>
                            </form>
                        </div>
                        <form method="post" action="{{ route('perfil.update') }}">
                            @csrf
                            <div class="row mt-2 gy-4">
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline">
                                        <input required class="form-control" type="text" id="nome" name="nome" value="{{ $user->nome }}"/>
                                        <label for="nome">Nome:</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline">
                                        <input required class="form-control" type="email" id="email" name="email" value="{{ $user->email }}"/>
                                        <label for="email">Email:</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="telefone" name="telefone" maxlength="15" onkeypress="mascara( this, mtel )" value="{{ $user->telefone }}"/>
                                        <label for="telefone">Telefone:</label>
                                    </div>
                                </div>
                                <div class="col-md-12 form-group">
                                    <button type="submit" class="btn btn-primary me-2">Salvar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card card-border-shadow-primary mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Segurança</h5>
                        <span>Gerencie sua senha de segurança.</span>
                        <hr>
                        <form action="{{ route('perfil.alterar_senha') }}" method="post">
                            @csrf
                            <div class="row gy-4 mt-2">
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline">
                                        <input required class="form-control" type="password" id="password" name="password"/>
                                        <label for="password">Senha Atual:</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline">
                                        <input required class="form-control" type="password" id="novo_password" name="novo_password"/>
                                        <label for="novo_password">Nova Senha:</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline">
                                        <input required class="form-control" type="password" id="confirma_password" name="confirma_password"/>
                                        <label for="confirma_password">Confirmar Senha:</label>
                                    </div>
                                </div>
                                <div class="col-md-12 form-group">
                                    <button type="submit" class="btn btn-primary me-2">Salvar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-border-shadow-primary mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Notificações</h5>
                        <span>Configure como deseja receber suas atualizações.</span>
                        <hr>
                        <div class="row mt-2 gy-4">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title mb-0">Notificações por Email</h6>
                                        <span style="font-size: 10px">Receber informações sobre investimentos</span>
                                    </div>
                                    <div style='margin-right: 10px !important'>
                                        <label class="switch">
                                            <input @if($user->notificacao_email == 'Sim') checked @endif id="checkbox_nofificacao" type="checkbox" value="Sim" class="switch-input">
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on"></span>
                                                <span class="switch-off"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title mb-0">Lembretes de Vencimento</h6>
                                        <span style="font-size: 10px">Avisos 7 dias antes do vencimento</span>
                                    </div>
                                    <div style='margin-right: 10px !important'>
                                        <label class="switch">
                                            <input @if($user->lembrete_vencimento == 'Sim') checked @endif id="checkbox_lembrete" value="Sim" type="checkbox" class="switch-input">
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on"></span>
                                                <span class="switch-off"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('resetar_foto_perfil').addEventListener('click', ()=>{
    if(confirm('Tem certeza que deseja excluir a imagem de foto?')){
        window.location.href = "{{ route('perfil.resetar_foto') }}"
    }
})

document.getElementById('checkbox_nofificacao').addEventListener('change', (e)=>{
    if(e.target.checked == true){
        notificacao = 'Sim';
    }
    else{
        notificacao = 'Não';
    }
    $.getJSON(
        '{{ route("perfil.setar_notificacoes") }}',
        { notificacao : notificacao },
        function(json){
        }
    );
})

document.getElementById('checkbox_lembrete').addEventListener('change', (e)=>{
    if(e.target.checked == true){
        lembrete = 'Sim';
    }
    else{
        lembrete = 'Não';
    }
    $.getJSON(
        '{{ route("perfil.setar_lembrete") }}',
        { lembrete : lembrete },
        function(json){
        }
    );
})
</script>
@endsection
