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
        Schema::create('subscricao', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor')->nullable()->default(0);
            $table->decimal('desconto')->default(0);
            $table->boolean('pagou')->nullable()->default(false);
            $table->foreignId('ano_id')->constrained('anos')->onDelete('cascade')->onUpdate('cascade'); 
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade')->onUpdate('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscricao');
    }
};
