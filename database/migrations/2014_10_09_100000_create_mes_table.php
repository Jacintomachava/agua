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
        Schema::create('mes', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->integer('numero')->unique();
            $table->timestamps();
        });

                // Dados para inserção inicial
        $dados = [
            ['id' => 1, 'nome' => 'Janeiro', 'numero' => 1, 'created_at' => '2024-03-22 13:51:48', 'updated_at' => '2024-03-22 13:51:48'],
            ['id' => 2, 'nome' => 'Fevereiro', 'numero' => 2, 'created_at' => '2024-03-22 13:51:48', 'updated_at' => '2024-03-22 13:51:48'],
            ['id' => 3, 'nome' => 'Março', 'numero' => 3, 'created_at' => '2024-03-22 13:51:48', 'updated_at' => '2024-03-22 13:51:48'],
            ['id' => 4, 'nome' => 'Abril', 'numero' => 4, 'created_at' => '2024-03-22 13:52:42', 'updated_at' => '2024-03-22 13:52:42'],
            ['id' => 5, 'nome' => 'Maio', 'numero' => 5, 'created_at' => '2024-04-08 12:53:41', 'updated_at' => '2024-04-08 12:53:41'],
            ['id' => 6, 'nome' => 'Junho', 'numero' => 6, 'created_at' => '2024-04-08 12:53:41', 'updated_at' => '2024-04-08 12:53:41'],
            ['id' => 7, 'nome' => 'Julho', 'numero' => 7, 'created_at' => '2024-04-24 21:28:51', 'updated_at' => '2024-04-24 21:28:51'],
            ['id' => 8, 'nome' => 'Agosto', 'numero' => 8, 'created_at' => '2024-04-24 21:29:30', 'updated_at' => '2024-04-24 21:29:30'],
            ['id' => 9, 'nome' => 'Setembro', 'numero' => 9, 'created_at' => '2024-04-24 21:29:30', 'updated_at' => '2024-04-24 21:29:30'],
            ['id' => 10, 'nome' => 'Outubro', 'numero' => 10, 'created_at' => '2024-04-24 21:30:52', 'updated_at' => '2024-04-24 21:30:52'],
            ['id' => 11, 'nome' => 'Novembro', 'numero' => 11, 'created_at' => '2024-04-24 21:32:32', 'updated_at' => '2024-04-24 21:32:32'],
            ['id' => 12, 'nome' => 'Dezembro', 'numero' => 12, 'created_at' => '2024-04-24 21:32:32', 'updated_at' => '2024-04-24 21:32:32'],
        ];

        DB::table('mes')->insert($dados);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mes');
    }
};
