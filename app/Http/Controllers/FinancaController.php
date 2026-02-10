<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Pagamento;
use App\Models\Leitura;
use App\Models\Despesa;
use App\Models\Mes;
use App\Models\Recibo;
use App\Models\CompraCredito;
use App\Models\FuroClienteContrato;

class FinancaController extends Controller
{
    public function index()
    {
        $userActual = Auth::user();
        $user = Auth::user();
        // Primeiro, obter o mês anterior
        $mesAnterior = Carbon::now()->subMonth()->month;
        $mesActual = Carbon::now()->month;
        $meses = Mes::orderBy('id')->get();

        //Cardes
        $pagamentoMes = Pagamento::where('empresa_id', $userActual->empresa_id)->where('furo_id', $user->furo_id)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('valor');
        $clientesDivida = FuroClienteContrato::where('empresa_id', $userActual->empresa_id)->where('furo_id', $user->furo_id)->sum('divida');
        $valorContractos = FuroClienteContrato::where('empresa_id', $userActual->empresa_id)->where('furo_id', $user->furo_id)->sum('saldo');
        $valorDespesa = Despesa::where('empresa_id', $userActual->empresa_id)->where('furo_id', $user->furo_id)->sum('valor_pago');
        $valorDespesaSistemaSMS = CompraCredito::where('empresa_id', $userActual->empresa_id)->where('furo_id', $user->furo_id)->sum('valor');

        //Tabela
        $recibos = Recibo::where('empresa_id', $userActual->empresa_id)->where('furo_id', $user->furo_id)->get();
        $despesas = Despesa::where('empresa_id', $userActual->empresa_id)->where('furo_id', $user->furo_id)->get();

        //Graficos Pia de Despesas
        $dados = Despesa::select(
            'despesa_categoria.nome as categoria',
                DB::raw('SUM(despesas.valor_pago) as total')
            )
            ->join('despesa_categoria', 'despesas.categoria_id', '=', 'despesa_categoria.id')
            ->where('empresa_id', $userActual->empresa_id)
            ->where('furo_id', $user->furo_id)
            ->groupBy('despesa_categoria.nome')
            ->get();

        $pieData = $dados->map(function ($item) {
            return [
                'name' => $item->categoria,
                'value' => (float)$item->total
            ];
        });

        //Graficos de Lucros
        $receitas = Leitura::select(
            'mes_id',
            DB::raw('SUM(valor_pago) as total')
        )
        ->where('empresa_id', $userActual->empresa_id)
        ->where('furo_id', $user->furo_id)
        ->groupBy('mes_id')
        ->get();

        // DESPESAS
        $despesas1 = Despesa::select(
                DB::raw('MONTH(data_pagamento) as mes_id'),
                DB::raw('SUM(valor_pago) as total')
            )
            ->where('empresa_id', $userActual->empresa_id)
            ->where('furo_id', $user->furo_id)
            ->groupBy(DB::raw('MONTH(data_pagamento)'))
            ->get();

        $labels = [];
        $receitaData = [];
        $despesaData = [];
        $lucroData = [];

        foreach ($meses as $mes) {

            $labels[] = $mes->nome;

            $r = $receitas->firstWhere('mes_id', $mes->id)->total ?? 0;
            $d = $despesas1->firstWhere('mes_id', $mes->id)->total ?? 0;

            $receitaData[] = (float)$r;
            $despesaData[] = (float)$d;
            $lucroData[] = (float)($r - $d);
        }

        //Tipo de banco
        $dados1 = Pagamento::select(
            'banco_carteira.nome as banco',
                DB::raw('SUM(pagamentos.valor) as total')
            )
            ->where('empresa_id', $userActual->empresa_id)
            ->where('furo_id', $user->furo_id)
            ->join('banco_carteira', 'pagamentos.tipo_banco', '=', 'banco_carteira.id')
            ->groupBy('banco_carteira.nome')
            ->get();

        $pieData1 = $dados1->map(function ($item) {
            return [
                'name' => $item->banco,
                'value' => (float)$item->total
            ];
        });

        //Leituras pagas
        $leituras = Leitura::select(
            'mes_id',
            DB::raw('SUM(valor_pago) as total')
        )
        ->where('empresa_id', $userActual->empresa_id)
        ->where('furo_id', $user->furo_id)
        ->groupBy('mes_id')
        ->get();

        $labels1 = [];
        $valores1 = [];

        foreach ($meses as $mes) {

            $labels1[] = $mes->nome;

            $total = $leituras->firstWhere('mes_id', $mes->id)->total ?? 0;

            $valores1[] = (float)$total;
        }

        return view('financas.index',  [
             'pagamentoMes' => $pagamentoMes,
             'clientesDivida' => $clientesDivida,
             'valorContractos' => $valorContractos,
             'valorDespesa' => $valorDespesa+$valorDespesaSistemaSMS,
             //Tabelas
             'recibos' => $recibos,
             'despesas' => $despesas,
             //Graficos
             'pieData' => $pieData,
             //Lucro
             'labels' => $labels,
             'receitaData' => $receitaData,
             'despesaData' => $despesaData,
             'lucroData' => $lucroData,
             //Tipo de banco
             'pieData1' => $pieData1,
             //Leituras pagas
             'labels1' => $labels1,
             'valores1' => $valores1,
        ]);
    }
}
