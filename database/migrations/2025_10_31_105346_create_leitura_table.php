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
        Schema::create('leituras', function (Blueprint $table) {
            $table->id();
            $table->integer('valor_leitura')->nullable()->default(0);
            $table->integer('consumo')->nullable()->default(0);
            $table->decimal('multa', 10, 2)->nullable()->default(0);
            $table->date('data_leitura')->nullable();
            $table->boolean('estado_leitura')->nullable()->default(false);  // 0 nao lido  e 1 lido 2 lido automaticamente
            $table->decimal('saldo', 10, 2)->nullable()->default(0);
            $table->decimal('valor_pago', 10, 2)->nullable()->default(0);
            $table->decimal('valor_a_pagar', 10, 2)->nullable()->default(0);
            $table->boolean('cliente_notificado')->nullable()->default(false);
            $table->date('ultimo_pagamento')->nullable();
            $table->enum('estado_pagamento', ['Pendente', 'Pago', 'Cancelado', 'Parcial', 'Isencao'])->nullable()->default('Pendente')->comment('Status do Pagamento');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade')->onUpdate('cascade'); 
            $table->foreignId('furo_cliente_contrato_id')->constrained('furo_cliente_contrato')->onDelete('cascade')->onUpdate('cascade'); 
            $table->foreignId('furo_id')->constrained('furos')->onDelete('cascade')->onUpdate('cascade'); 
            $table->foreignId('ano_id')->constrained('anos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('mes_id')->constrained('mes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('leitura_feita_por')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['furo_cliente_contrato_id', 'ano_id','mes_id'], 'unica_leitura_por_mes_ano');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leituras');
    }
};
