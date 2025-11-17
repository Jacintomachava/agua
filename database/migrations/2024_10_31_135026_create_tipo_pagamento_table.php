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
        Schema::create('tipo_pagamento', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->nullable();
            $table->string('descricao')->nullable();
            $table->string('codigo')->nullable(); // 1 para as empresa e 2 para Saas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_pagamento');
    }
};
