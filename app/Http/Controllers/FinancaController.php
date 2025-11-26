<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Pagamento;
use App\Models\Leitura;
use App\Models\FuroClienteContrato;

class FinancaController extends Controller
{
    public function index()
    {
        $userActual = Auth::user();
        // Primeiro, obter o mês anterior
        $mesAnterior = Carbon::now()->subMonth()->month;

        $totalPagoHoje = Pagamento::where('empresa_id', $userActual->empresa_id)
            ->whereDate('created_at', Carbon::today())
            ->sum('valor');

        $totalPendente = Leitura::where('empresa_id', $userActual->empresa_id)
            ->where('mes_id', $mesAnterior)
            ->where('estado_pagamento','Pendente')
            ->select(DB::raw('COALESCE(SUM(valor_a_pagar * (1 + multa/100)), 0) as total'))
            ->value('total');

        $totalContratos = FuroClienteContrato::where('empresa_id', $userActual->empresa_id)->sum('valor_pago');

        $totalMesAnterior = Pagamento::where('empresa_id', $userActual->empresa_id)
            ->whereHas('fatura.leitura', function($query) use ($mesAnterior) {
                $query->where('mes_id', $mesAnterior);
            })
            ->sum('valor');

        $pagamentos = Pagamento::where('empresa_id', $userActual->empresa_id)
            ->whereHas('fatura.leitura', function($query) use ($mesAnterior) {
                $query->where('mes_id', $mesAnterior);
            })
            ->get();

        return view('financas.index',  [
             'totalPagoHoje' => $totalPagoHoje,
             'totalMesAnterior' => $totalMesAnterior,
             'totalContratos' => $totalContratos,
             'totalPendente' => $totalPendente,
             'pagamentos' => $pagamentos,
        ]);
    }
}
