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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('sigla')->nullable();
            $table->string('nuit')->nullable()->unique();
            $table->string('telefone')->nullable()->unique();
            $table->string('endereco')->nullable();
            $table->boolean('estado')->nullable()->default(true);
            $table->boolean('subscricao')->nullable()->default(true);
            $table->boolean('mensalidade')->nullable()->default(true);
            $table->string('logotipo')->nullable()->unique();
            $table->foreignId('distrito_id')->constrained('distritos')->onDelete('cascade')->onUpdate('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
