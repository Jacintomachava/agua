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
       $saldo = SaldoSMS::where('empresa_id',$userActual->empresa_id)->first();
       $leituras = Leitura::where('empresa_id',$userActual->empresa_id)->where('estado_leitura',0)->where('mes_id',$mesAtual-1)->get();
       $clientes = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->get();
       $users = User::where('empresa_id',$userActual->empresa_id)->get();

       $tubagens = Tubagem::where('empresa_id',$userActual->empresa_id)->get();

        return view('home',  [
             'saldo' => $saldo,
             'clientes' => $clientes,
             'leituras' => $leituras,
             'users' => $users,
             'mesActualAnterior' => $mesActualAnterior,
             'tubagens' => $tubagens,
        ]);
    }
}
