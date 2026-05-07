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
        Schema::create('cotas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->double('vl_carta',10,2);
            $table->integer('parcelas');
            $table->double('vl_parcela',10,2);
            $table->date('dt_compra');
            $table->date('dt_venda');
            $table->integer('dias_venda');
            $table->double('vl_compra',10,2);
            $table->double('vl_venda',10,2);
            $table->double('lucro_bruto',10,2);
            $table->double('lucro_ratiado',10,2);
            $table->double('comissao',10,2);
            $table->double('lucro_liquido',10,2);
            $table->string('situacao');
            $table->text('obs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotas');
    }
};
