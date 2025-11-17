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
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao')->nullable();
            $table->decimal('valor', 10, 2)->nullable()->default(0);
            $table->foreignId('furo_id')->constrained('furos')->onDelete('cascade')->onUpdate('cascade'); 
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->onDelete('cascade');
            $table->foreignId('factura_id')->nullable()->constrained('facturas')->onDelete('cascade');
            $table->foreignId('leitura_id')->nullable()->constrained('leituras')->onDelete('cascade');
            $table->foreignId('subscricao_id')->nullable()->constrained('subscricao')->onDelete('cascade');
            $table->foreignId('mensalidade_id')->nullable()->constrained('mensalidades')->onDelete('cascade');
            $table->enum('estado', ['Completo', 'Parcial', 'Isencao'])->comment('Status da factura');
            $table->foreignId('forma_pagamento_id')->nullable()->constrained('forma_pagamento')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('tipo_pagamento_id')->nullable()->constrained('tipo_pagamento')->onDelete('cascade')->onUpdate('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
