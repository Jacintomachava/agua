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
        Schema::create('sms_credito', function (Blueprint $table) {
            $table->id();
            $table->decimal('saldo', 10, 2)->default(0);
            $table->string('notificado')->nullable()->default(false);
            $table->foreignId('empresa_id')->unique()->constrained('empresas')->onDelete('cascade')->onUpdate('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_credito');
    }
};
