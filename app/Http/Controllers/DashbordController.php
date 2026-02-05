<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaldoSMS;
use App\Models\Ano;
use App\Models\Mes;
use App\Models\Leitura;
use App\Models\FuroClienteContrato;
use App\Models\User;
use App\Models\Tubagem;
use App\Models\Pagamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashbordController extends Controller
{
    public function indexHome()
    {

       $userActual = Auth::user();
       $anoActual =  Carbon::now()->year;
       $mesAtual = Carbon::now()->month;
       $ano = Ano::where('ano',$anoActual)->first();
       $mesActualAnterior = Mes::where('numero',$mesAtual-1)->first();
       $saldo = SaldoSMS::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->first();
       $leituras = Leitura::where('empresa_id',$userActual->empresa_id)->where('estado_leitura',0)->where('furo_id',$userActual->furo_id)->where('mes_id',$mesAtual-1)->get();
       $clientes = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();

       //Grafico de Consumo por Mes
       // Buscar todos os meses
       $meses = Mes::orderBy('id')->get();

       $labels = [];
       $valores = [];

       foreach ($meses as $mes) {
            $totalConsumo = Leitura::where('ano_id', $ano->id)
                ->where('mes_id', $mes->id)
                ->where('furo_id',$userActual->furo_id)
                ->where('empresa_id',$userActual->empresa_id)
                ->sum('consumo');

            $labels[] = $mes->nome;
            $valores[] = $totalConsumo;
        }

        //Forma de pagamento Numero
        $dados = Pagamento::select(
            'banco_carteira.nome as nome',
            DB::raw('COUNT(pagamentos.id) as total')
        )
        ->join('banco_carteira', 'pagamentos.tipo_banco', '=', 'banco_carteira.id')
        ->where('empresa_id',$userActual->empresa_id)
        ->where('furo_id',$userActual->furo_id)
        ->groupBy('banco_carteira.nome')
        ->orderBy('total', 'desc')
        ->get();

        $labels1 = $dados->pluck('nome');
        $valores1 = $dados->pluck('total');

        //Leituras
        $leiturasFeitas = Leitura::where('estado_leitura', 1)->where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->count();
        $leiturasPendentes = Leitura::where('estado_leitura', 0)->where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->count();

        //Clientes Activo 
        $clientesAtivos = FuroClienteContrato::where('ligacao_activa', 1)->where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->count();
        $clientesInativos = FuroClienteContrato::where('ligacao_activa', 0)->where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->count();


        return view('home',  [
             'saldo' => $saldo,
             'clientes' => $clientes,
             'leituras' => $leituras,
             'mesActualAnterior' => $mesActualAnterior,
             //Graficos de Consumo mes
             'labels' => $labels,
             'valores' => $valores,
             //Graficos de Consumo mes
             'labels1' => $labels1,
             'valores1' => $valores1,
             //Leituras
             'leiturasFeitas' => $leiturasFeitas,
             'leiturasPendentes' => $leiturasPendentes,
             //Clientes 
             'clientesAtivos' => $clientesAtivos,
             'clientesInativos' => $clientesInativos,
        ]);
    }
}
