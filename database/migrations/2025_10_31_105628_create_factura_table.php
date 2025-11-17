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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('empresas')->onDelete('cascade');   //Empresa Que Recebem a factura e deve pagar
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');   //Pessoa Que Recebem a factura e deve pagar
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');  //Pesoa que espera receber o pagamento
            $table->string('numero_factura')->comment('Número único da factura');
            $table->date('data_emissao');
            $table->enum('status', ['Pendente', 'Pago', 'Cancelado', 'Parcial', 'Revisao'])->comment('Status da factura');
            $table->foreignId('factura_id')->nullable()->constrained('facturas')->onDelete('cascade');
            $table->foreignId('leitura_id')->nullable()->constrained('leituras')->onDelete('cascade');
            $table->foreignId('subscricao_id')->nullable()->constrained('subscricao')->onDelete('cascade');
            $table->foreignId('mensalidade_id')->nullable()->constrained('mensalidades')->onDelete('cascade');
            $table->decimal('valor', 10, 2)->comment('Valor total da factura');
            $table->unique(['empresa_id', 'numero_factura'], 'unique_numero_factura_empresa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
