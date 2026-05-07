<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Investidor;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('investidors', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();
            $table->string('cpf')->nullable();
            $table->string('cep')->nullable();
            $table->text('endereco')->nullable();
            $table->text('numero')->nullable();
            $table->text('complemento')->nullable();
            $table->text('bairro')->nullable();
            $table->text('cidade')->nullable();
            $table->string('uf')->nullable();
            $table->string('banco')->nullable();
            $table->string('tipo_conta')->nullable();
            $table->string('agencia')->nullable();
            $table->string('nr_conta')->nullable();
            $table->string('chave_pix')->nullable();
            $table->string('token_doc_zap_sign')->nullable();
            $table->string('assinatura_contrato_investimento', 5)->default('Não');
            $table->unsignedBigInteger('user_id_indicador')->nullable();
            $table->string('st_renda_total',5)->default('Não');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_id_indicador')->references('id')->on('users');
            $table->timestamps();
        });

        //vamos criar o arismar Amorin e o Anderson Amorin
        $dados = [
            'id' => '2',
            'nome' => 'Arismar Amorin Investidor',
            'email' => 'arismar_investidor@teste.com',
            'password' => bcrypt('arismar'),
            'tipo' => 'Investidor',
        ];

        $user = User::create($dados);

        $dados = [
            'user_id' => $user->id,
            'assinatura_contrato_investimento' => 'Sim',
        ];

        Investidor::create($dados);

        $dados = [
            'id' => '3',
            'nome' => 'Anderson Amorin Investidor',
            'email' => 'anderson_investidor@teste.com',
            'password' => bcrypt('anderson'),
            'tipo' => 'Investidor',
        ];

        $user = User::create($dados);

        $dados = [
            'user_id' => $user->id,
            'assinatura_contrato_investimento' => 'Sim',
        ];

        Investidor::create($dados);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investidors');
    }
};
