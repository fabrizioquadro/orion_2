@extends('layout.sistema')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                <i class="menu-icon tf-icons mdi mdi-account"></i>
                Adicionar Usuário
            </h4>
        </div>
        <hr>
        <form action="{{ route('adm.usuarios.insert') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row mt-2 gy-4">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="nome" name="nome"/>
                        <label for="nome">Nome:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="email" id="email" name="email"/>
                        <label for="email">E-mail:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="telefone" name="telefone" maxlength="15" onkeypress="mascara( this, mtel )"/>
                        <label for="telefone">Telefone:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="password" id="password" name="password" />
                        <label for="password">Senha:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <select required id="tipo" name='tipo' class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Promotor">Promotor</option>
                            <option value="Vendedor">Vendedor</option>
                        </select>
                        <label for="tipo">Tipo:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <select required id="indicacao_anderson" name='indicacao_anderson' class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="Sim">Sim</option>
                            <option value="Não">Não</option>
                        </select>
                        <label for="indicacao_anderson">Indicado por Anderson:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="file" id="imagem" name="imagem"/>
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
