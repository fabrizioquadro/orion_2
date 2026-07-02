<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardAdmController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\InvestidorController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\InvestimentoAdmController;
use App\Http\Controllers\TransacaoAdmController;
use App\Http\Controllers\DashboardInvestidorController;
use App\Http\Controllers\DashboardInvestidorV2Controller;
use App\Http\Controllers\InvestimentoInvestidorController;
use App\Http\Controllers\TransacaoInvestidorController;
use App\Http\Controllers\CotaController;
use App\Http\Controllers\RelatorioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'index'])->name('index');
Route::get('/teste', [LoginController::class, 'teste'])->name('teste');
Route::get('/esqueceu_senha', [LoginController::class, 'esqueceu_senha'])->name('esqueceu_senha');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/recuperar_senha', [LoginController::class, 'recuperar_senha'])->name('recuperar_senha');
Route::get('/cadastrar', [LoginController::class, 'cadastrar'])->name('cadastrar');
Route::post('/register', [LoginController::class, 'register'])->name('register');

Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [DashboardAdmController::class, 'perfil'])->name('perfil');
    Route::get('/perfil/setar_notificacoes', [DashboardAdmController::class, 'setar_notificacoes'])->name('perfil.setar_notificacoes');
    Route::get('/perfil/setar_lembrete', [DashboardAdmController::class, 'setar_lembrete'])->name('perfil.setar_lembrete');
    Route::post('/perfil/update', [DashboardAdmController::class, 'update'])->name('perfil.update');
    Route::post('/perfil/atualizar_foto', [DashboardAdmController::class, 'atualizar_foto'])->name('perfil.atualizar_foto');
    Route::post('/perfil/alterar_senha', [DashboardAdmController::class, 'alterar_senha'])->name('perfil.alterar_senha');
    Route::get('/resetar_foto', [DashboardAdmController::class, 'resetar_foto'])->name('perfil.resetar_foto');

    Route::middleware(['verificaAdministrador'])->group(function () {

        Route::prefix('adm')->group(function(){

            Route::get('/dashboard', [DashboardAdmController::class, 'index'])->name('adm.dashboard');
            Route::get('/dashboard/confirma_investimento/{id}', [DashboardAdmController::class, 'confirma_investimento'])->name('adm.dashboard.confirma_investimento');
            Route::get('/dashboard/confirma_resgate/{id}', [DashboardAdmController::class, 'confirma_resgate'])->name('adm.dashboard.confirma_resgate');

            Route::get('/usuarios', [UsuarioController::class, 'index'])->name('adm.usuarios');
            Route::get('/usuarios/adicionar', [UsuarioController::class, 'adicionar'])->name('adm.usuarios.adicionar');
            Route::get('/usuarios/editar/{id}', [UsuarioController::class, 'editar'])->name('adm.usuarios.editar');
            Route::get('/usuarios/excluir/{id}', [UsuarioController::class, 'excluir'])->name('adm.usuarios.excluir');
            Route::get('/usuarios/alterar_senha/{id}', [UsuarioController::class, 'alterar_senha'])->name('adm.usuarios.alterar_senha');
            Route::post('/usuarios/insert', [UsuarioController::class, 'insert'])->name('adm.usuarios.insert');
            Route::post('/usuarios/update', [UsuarioController::class, 'update'])->name('adm.usuarios.update');
            Route::post('/usuarios/delete', [UsuarioController::class, 'delete'])->name('adm.usuarios.delete');
            Route::post('/usuarios/update_senha', [UsuarioController::class, 'update_senha'])->name('adm.usuarios.update_senha');

            Route::get('/investidores', [InvestidorController::class, 'index'])->name('adm.investidores');
            Route::get('/investidores/adicionar', [InvestidorController::class, 'adicionar'])->name('adm.investidores.adicionar');
            Route::get('/investidores/editar/{id}', [InvestidorController::class, 'editar'])->name('adm.investidores.editar');
            Route::get('/investidores/alterar_senha/{id}', [InvestidorController::class, 'alterar_senha'])->name('adm.investidores.alterar_senha');
            Route::get('/investidores/excluir/{id}', [InvestidorController::class, 'excluir'])->name('adm.investidores.excluir');
            Route::get('/investidores/arquivos/{id}', [InvestidorController::class, 'arquivos'])->name('adm.investidores.arquivos');
            Route::post('/investidores/insert', [InvestidorController::class, 'insert'])->name('adm.investidores.insert');
            Route::post('/investidores/update', [InvestidorController::class, 'update'])->name('adm.investidores.update');
            Route::post('/investidores/delete', [InvestidorController::class, 'delete'])->name('adm.investidores.delete');
            Route::post('/investidores/update_senha', [InvestidorController::class, 'update_senha'])->name('adm.investidores.update_senha');
            Route::post('/investidores/arquivos_update', [InvestidorController::class, 'arquivos_update'])->name('adm.investidores.arquivos_update');
            Route::get('/investidores/arquivos_delete/{id}', [InvestidorController::class, 'arquivos_delete'])->name('adm.investidores.arquivos_delete');

            Route::get('/configuracoes', [ConfiguracaoController::class, 'index'])->name('adm.configuracoes');
            Route::post('/configuracoes/setar', [ConfiguracaoController::class, 'setar'])->name('adm.configuracoes.setar');
            Route::post('/configuracoes/contrato', [ConfiguracaoController::class, 'contrato'])->name('adm.configuracoes.contrato');

            Route::get('/investimentos', [InvestimentoAdmController::class, 'index'])->name('adm.investimentos');
            Route::get('/investimentos/adicionar', [InvestimentoAdmController::class, 'adicionar'])->name('adm.investimentos.adicionar');
            Route::get('/investimentos/analise_situacao', [InvestimentoAdmController::class, 'analise_situacao'])->name('adm.investimentos.analise_situacao');
            Route::get('/investimentos/editar/{id}', [InvestimentoAdmController::class, 'editar'])->name('adm.investimentos.editar');
            Route::get('/investimentos/excluir/{id}', [InvestimentoAdmController::class, 'excluir'])->name('adm.investimentos.excluir');
            Route::get('/investimentos/finalizar/{id}', [InvestimentoAdmController::class, 'finalizar'])->name('adm.investimentos.finalizar');
            Route::post('/investimentos/insert', [InvestimentoAdmController::class, 'insert'])->name('adm.investimentos.insert');
            Route::post('/investimentos/update', [InvestimentoAdmController::class, 'update'])->name('adm.investimentos.update');
            Route::post('/investimentos/delete', [InvestimentoAdmController::class, 'delete'])->name('adm.investimentos.delete');
            Route::post('/investimentos/finalizar_set', [InvestimentoAdmController::class, 'finalizar_set'])->name('adm.investimentos.finalizar_set');

            Route::get('/transacoes', [TransacaoAdmController::class, 'index'])->name('adm.transacoes');
            Route::post('/transacoes/gerar', [TransacaoAdmController::class, 'gerar'])->name('adm.transacoes.gerar');
            Route::post('/transacoes/reinvestir', [TransacaoAdmController::class, 'reinvestir'])->name('adm.transacoes.reinvestir');
            Route::post('/transacoes/transferir', [TransacaoAdmController::class, 'transferir'])->name('adm.transacoes.transferir');
            Route::post('/transacoes/resgatar', [TransacaoAdmController::class, 'resgatar'])->name('adm.transacoes.resgatar');
            Route::post('/transacoes/resgatar/update', [TransacaoAdmController::class, 'resgatar_update'])->name('adm.transacoes.resgatar.update');
            Route::post('/transacoes/resgatar/delete', [TransacaoAdmController::class, 'resgatar_delete'])->name('adm.transacoes.resgatar.delete');
            Route::get('/transacoes/get_resgate', [TransacaoAdmController::class, 'get_resgate'])->name('adm.transacoes.get_resgate');

            Route::get('/cotas', [CotaController::class, 'index'])->name('adm.cotas');
            Route::get('/cotas/adicionar', [CotaController::class, 'adicionar'])->name('adm.cotas.adicionar');
            Route::get('/cotas/acessar/{id}', [CotaController::class, 'acessar'])->name('adm.cotas.acessar');
            Route::get('/cotas/excluir/{id}', [CotaController::class, 'excluir'])->name('adm.cotas.excluir');
            Route::get('/cotas/editar/{id}', [CotaController::class, 'editar'])->name('adm.cotas.editar');
            Route::post('/cotas/insert', [CotaController::class, 'insert'])->name('adm.cotas.insert');
            Route::post('/cotas/update', [CotaController::class, 'update'])->name('adm.cotas.update');
            Route::post('/cotas/finalizar', [CotaController::class, 'finalizar_cota'])->name('adm.cotas.finalizar');
            Route::post('/cotas/abrir', [CotaController::class, 'abrir_cota'])->name('adm.cotas.abrir');
            Route::post('/cotas/delete', [CotaController::class, 'delete'])->name('adm.cotas.delete');

            Route::get('/rel/comissoes', [RelatorioController::class, 'index_comissoes'])->name('adm.relatorios.comissoes');
            Route::get('/rel/saques', [RelatorioController::class, 'index_saques'])->name('adm.relatorios.saques');
            Route::get('/rel/ganhos', [RelatorioController::class, 'index_ganhos'])->name('adm.relatorios.ganhos');
            Route::get('/rel/cotas', [RelatorioController::class, 'index_cotas'])->name('adm.relatorios.cotas');
            Route::get('/rel/investimentos', [RelatorioController::class, 'index_investimentos'])->name('adm.relatorios.investimentos');
            Route::post('/rel/comissoes/gerar', [RelatorioController::class, 'gerar_comissoes'])->name('adm.relatorios.gerar_comissoes');
            Route::post('/rel/saques/gerar', [RelatorioController::class, 'gerar_saques'])->name('adm.relatorios.gerar_saques');
            Route::post('/rel/ganhos/gerar', [RelatorioController::class, 'gerar_ganhos'])->name('adm.relatorios.gerar_ganhos');
            Route::post('/rel/cotas/gerar', [RelatorioController::class, 'gerar_cotas'])->name('adm.relatorios.gerar_cotas');
            Route::post('/rel/investimentos/gerar', [RelatorioController::class, 'gerar_investimentos'])->name('adm.relatorios.gerar_investimentos');

        });

    });

    Route::prefix('investidor')->group(function(){
        Route::get('/dashboard', [DashboardInvestidorV2Controller::class, 'index'])->name('investidor.dashboard');
        Route::get('/dashboard_old', [DashboardInvestidorController::class, 'index'])->name('investidor.dashboard_old');

        Route::get('/investimentos', [InvestimentoInvestidorController::class, 'index'])->name('investidor.investimentos');
        Route::get('/investimentos/adicionar', [InvestimentoInvestidorController::class, 'adicionar'])->name('investidor.investimentos.adicionar');
        Route::post('/investimentos/insert', [InvestimentoInvestidorController::class, 'insert'])->name('investidor.investimentos.insert');

        Route::get('/transacoes', [TransacaoInvestidorController::class, 'index'])->name('investidor.transacoes');
        Route::post('/transacoes/reinvestir', [TransacaoInvestidorController::class, 'reinvestir'])->name('investidor.transacoes.reinvestir');
        Route::post('/transacoes/resgatar', [TransacaoInvestidorController::class, 'resgatar'])->name('investidor.transacoes.resgatar');
    });
});
