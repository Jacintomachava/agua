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
        Schema::create('recibo', function (Blueprint $table) {
            $table->id();
            $table->string('numero_factura')->comment('Número único da factura');
            $table->enum('status', ['Pendente', 'Pago', 'Cancelado', 'Parcial', 'Revisao'])->nullable()->comment('Status da factura');
            $table->decimal('valor', 10, 2)->comment('Valor total da factura');
            $table->foreignId('pagamento_id')->nullable()->constrained('pagamentos')->onDelete('cascade');
            $table->foreignId('factura_id')->nullable()->constrained('facturas')->onDelete('cascade');
            $table->foreignId('leitura_id')->nullable()->constrained('leituras')->onDelete('cascade');
            $table->foreignId('subscricao_id')->nullable()->constrained('subscricao')->onDelete('cascade');
            $table->foreignId('mensalidade_id')->nullable()->constrained('mensalidades')->onDelete('cascade');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');  //Pesoa que espera receber o pagamento
            $table->foreignId('furo_id')->constrained('furos')->onDelete('cascade')->onUpdate('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recibo');
    }
};
