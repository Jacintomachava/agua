<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BancoCarteira;

class BancoCarteiraController extends Controller
{
    //
    public function apiBancosCarteiras($formaPagamentoID)
    {

        $bancosCarteiras = BancoCarteira::where('forma_pagamento_id',$formaPagamentoID)->get();

        return response()->json($bancosCarteiras);
    }
}
