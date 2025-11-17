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
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor_despesa', 10, 2)->nullable()->default(0);
            $table->string('descricao')->nullable();
            $table->date('data_despesa')->nullable();
            $table->enum('estado', ['Pago','Completo', 'Pago Parcial', 'Isencao'])->comment('Status da factura');
            $table->decimal('saldo', 10, 2)->nullable()->default(0);
            $table->decimal('valor_pago', 10, 2)->nullable()->default(0);
            $table->date('ultimo_pagamento')->nullable();
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->onDelete('cascade');
            $table->foreignId('furo_id')->constrained('furos')->onDelete('cascade')->onUpdate('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despesas');
    }
};
