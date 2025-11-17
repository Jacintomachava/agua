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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->integer('consumo_minimo');
            $table->decimal('valor', 10, 2);
            $table->string('designacao');
            $table->string('descricao');
            $table->integer('metro_cubico');
            $table->integer('multa');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade')->onUpdate('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
