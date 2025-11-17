<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrato;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ContratoController extends Controller
{
    public function index()
    {
        $userActual = Auth::user();

        $contratos = Contrato::where('empresa_id',$userActual->empresa_id)->get();

        return view('contratos.index',  [
             'contratos' => $contratos,
        ]);
    }

    public function create()
    {
        return view('contratos.create',  [

        ]);
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            $userActual = Auth::user();

            // Cria Empresa e colocar Saldo de SMSCredito
            $contrato = new Contrato();
            $contrato->consumo_minimo = $request->input('consumo_minimo');
            $contrato->valor_contrato = $request->input('valor_contrato');
            $contrato->valor = $request->input('valor_consumo');
            $contrato->nome = $request->input('nome');
            $contrato->prazo_pagamento = $request->input('prazo_pagamento');
            $contrato->metro_cubico = $request->input('consumo');
            $contrato->multa = $request->input('multa');
            $contrato->empresa_id = $userActual->empresa_id;

            if ($contrato->save()) {

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Contrato Registado Com Sucesso']);

            }

        } catch (\Exception $e) {
            DB::rollBack();
            //$errorMessage = DatabaseErrorHandler::handle($e);
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }

}
