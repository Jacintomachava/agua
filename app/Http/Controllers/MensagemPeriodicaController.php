<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Furo;
use App\Services\SMSService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MensagemPeriodicaController extends Controller
{
    //
    public function create()
    {
        $userActual = Auth::user();

         $furos = null;
        
        if (auth()->user()->hasRole('Admin')) {
            // usuário é admin
            $furos = Furo::where('empresa_id',$userActual->empresa_id)->get();
        }

        if (auth()->user()->hasRole('SuperAdmin')) {
            // usuário é admin
            $furos = Furo::where('empresa_id',$userActual->empresa_id)->get();
        }

        if (auth()->user()->hasRole('Leitura')) {
            // usuário é Leitura
            $furos = Furo::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();
        }

        
        return view('mensagemperiodica.create1',  [
                'furos' => $furos
        ]);
    }

}
