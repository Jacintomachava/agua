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
        Schema::create('mensagem', function (Blueprint $table) {
            $table->id();
            $table->text('descricao');
            $table->string('telefone');
            $table->enum('tipo', ['Recebida', 'Enviada', 'Pendente'])->nullable()->default('Pendente')->comment('Status do Pagamento');
            $table->integer('qtd')->nullable()->default(1);
            $table->decimal('credito',10,2)->nullable()->default(1);
            $table->decimal('custo_real',10,2)->nullable()->default(1);
            $table->enum('canal', ['SMS', 'WhatsApp'])->nullable()->default('SMS')->comment('Status do Pagamento');
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->onDelete('cascade');
            $table->foreignId('furo_id')->nullable()->constrained('furos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensagem');
    }
};
