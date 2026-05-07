<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Configuracao;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('configuracaos', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('tempo_investimento');
            $table->double('minimo_investimento',10,2);
            $table->double('ate_ciquenta');
            $table->double('ate_cem');
            $table->double('acima_cem');
            $table->text('modelo_contrato')->nullable();
            $table->timestamps();
        });

        $dados = [
            'id' => '1',
            'tempo_investimento' => '60',
            'minimo_investimento' => '10000.00',
            'ate_ciquenta' => '3.0',
            'ate_cem' => '3.5',
            'acima_cem' => '4.0',
        ];

        Configuracao::create($dados);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracaos');
    }
};
