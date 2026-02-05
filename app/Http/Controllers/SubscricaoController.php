<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensalidade;
use App\Models\SaldoSMS;
use App\Models\Empresa;
use App\Models\Mensagem;
use App\Models\Mes;
use App\Models\Leitura;
use App\Models\CompraCredito;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SubscricaoController extends Controller
{
    public function index()
    {
        $userActual = Auth::user();
        $anoAtual = Carbon::now()->year;

        $empresa = Empresa::where('id',$userActual->empresa_id)->first();
        $saldo = SaldoSMS::where('empresa_id',$userActual->empresa_id)->first();
        $pacotes = CompraCredito::where('empresa_id',$userActual->empresa_id)->orderBy('updated_at', 'desc')->get();
        $leituras = Leitura::where('mes_id',Carbon::now()->subMonth()->month)->where('empresa_id',$userActual->empresa_id)->orderBy('updated_at', 'desc')->get();
        $mensagens = Mensagem::where('empresa_id',$userActual->empresa_id)->orderBy('updated_at', 'desc')->get();

        // Buscar meses
        $meses = Mes::orderBy('id')->get();
        // Somar crédito por mês do ano atual
        $dados1 = Mensagem::select(
                DB::raw('MONTH(updated_at) as mes_id'),
                DB::raw('SUM(credito) as total_credito')
            )
            ->whereYear('updated_at', $anoAtual)
            ->where('empresa_id',$userActual->empresa_id)
            //->where('tipo','Enviada')
            ->groupBy(DB::raw('MONTH(updated_at)'))
            ->get();


        // Somar crédito apenas das leituras feitas
        $dados = Leitura::select(
                'mes_id',
                DB::raw('SUM(credito) as total_credito')
            )
            ->where('estado_leitura', 1) // ✅ apenas leituras feitas
            ->groupBy('mes_id')
            ->get();

        $labels1 = [];
        $valores1 = [];

        foreach ($meses as $mes) {
            $labels1[] = $mes->nome;

            $registro = $dados1->firstWhere('mes_id', $mes->id);
            $valores1[] = $registro ? (float) $registro->total_credito : 0;
        }

        $labels = [];
        $valores = [];

        foreach ($meses as $mes) {
            $labels[] = $mes->nome;

            $registro = $dados->firstWhere('mes_id', $mes->id);
            $valores[] = $registro ? (float) $registro->total_credito : 0;
        }

        // Retorna a view com os dados carregados
        return view('subscricao.index', [
            'empresa' => $empresa,
            'saldo' => $saldo,
            'pacotes' => $pacotes,
            'labels' => $labels,
            'valores' => $valores,
            //Grafico de Sistema
            'labels1' => $labels1,
            'valores1' => $valores1,
            //Leituras
            'leituras' => $leituras,
            'mensagens' => $mensagens,
        ]);
    }
}
