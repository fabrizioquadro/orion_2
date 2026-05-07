<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('investimentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('titulo')->nullable();
            $table->date('dt_investimento');
            $table->double('vl_investimento',10,2);
            $table->date('dt_retorno');
            $table->double('vl_retorno_ganho',10,2);
            $table->string('st_investimento');
            $table->string('reinvestimento',5)->nullable();
            $table->string('investimento_cofirmado',5);
            $table->double('vl_cota_investido',10,2);
            $table->double('vl_cota_restante',10,2);
            $table->string('retorno_comissao',5)->default('Não');
            $table->string('comprovante')->nullable();
            $table->double('indice_rendimento')->nullable();
            $table->string('token_doc_zap_sign')->nullable();
            $table->string('assinatura_contrato_investimento',5)->default('Não');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investimentos');
    }
};
