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
        Schema::create('cota_investimentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cota_id');
            $table->unsignedBigInteger('investimento_id');
            $table->double('vl_investimento',10,2);
            $table->foreign('cota_id')->references('id')->on('cotas');
            $table->foreign('investimento_id')->references('id')->on('investimentos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cota_investimentos');
    }
};
