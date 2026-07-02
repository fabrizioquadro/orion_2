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
        Schema::table('cotas', function (Blueprint $table) {
            $table->decimal('comissao_orion', 15, 2)->default(0.00)->after('comissao');
            $table->enum('aplica_comissao_orion', ['Sim', 'Não'])->default('Não')->after('comissao_orion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cotas', function (Blueprint $table) {
            $table->dropColumn(['comissao_orion', 'aplica_comissao_orion']);
        });
    }
};
