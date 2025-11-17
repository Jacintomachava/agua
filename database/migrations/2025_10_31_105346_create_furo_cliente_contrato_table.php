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
        Schema::create('furo_cliente_contrato', function (Blueprint $table) {
            $table->id();
            $table->string('contador')->nullable()->unique();
            $table->decimal('saldo', 10, 2)->nullable()->default(0);
            $table->decimal('valor_pago', 10, 2)->nullable()->default(0);
            $table->decimal('valor_a_pagar', 10, 2)->nullable()->default(0);
            $table->enum('estado_pagamento', ['Pendente', 'Pago', 'Cancelado', 'Parcial', 'Isencao'])->nullable()->default('Pendente')->comment('Status do Pagamento');  
            $table->date('ultimo_pagamento')->nullable();
            $table->decimal('latitude', 10, 2)->nullable()->default(0)->comment('Local para Fazer Leitura');
            $table->decimal('longitude', 10, 2)->nullable()->default(0)->comment('Local para Fazer Leitura');
            $table->string('bairro')->nullable();
            $table->string('quarteirao')->nullable();
            $table->string('casa')->nullable();
            $table->string('telefone_notificar')->nullable();
            $table->boolean('localizacao_activa')->nullable()->default(false);
            $table->boolean('ligacao_activa')->nullable()->default(true);
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade')->onUpdate('cascade'); 
            $table->foreignId('contrato_id')->constrained('contratos')->onDelete('cascade')->onUpdate('cascade'); 
            $table->foreignId('furo_id')->constrained('furos')->onDelete('cascade')->onUpdate('cascade'); 
            $table->foreignId('distrito_id')->constrained('distritos')->onDelete('cascade')->onUpdate('cascade'); 
            $table->foreignId('provincia_id')->constrained('provincias')->onDelete('cascade')->onUpdate('cascade'); 
            $table->foreignId('ano_inicio_id')->constrained('anos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('mes_inicio_id')->constrained('mes')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('furo_cliente_contrato');
    }
};
