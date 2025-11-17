<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrato;
use App\Models\FuroClienteContrato;
use App\Models\Cliente;
use App\Models\Provincia;
use App\Models\Pagamento;
use App\Models\FormaPagamento;
use App\Models\Fatura;
use App\Models\Recibo;
use App\Models\Ano;
use App\Models\Leitura;
use App\Models\Mes;
use App\Models\BancoCarteira;
use App\Models\Furo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeituraController extends Controller
{
    public function index()
    {
        $userActual = Auth::user();

        $leituras = null;
        $furos = null;
        $provincias = Provincia::all();
        
        if (auth()->user()->hasRole('Admin')) {
            // usuário é admin
            $leituras = Leitura::where('empresa_id',$userActual->empresa_id)->get();
        }

        if (auth()->user()->hasRole('SuperAdmin')) {
            // usuário é admin
            $leituras = Leitura::where('empresa_id',$userActual->empresa_id)->get();
        }

        if (auth()->user()->hasRole('Leitura')) {
            // usuário é Leitura
            $leituras = Leitura::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();
        }

        
        return view('leitura.index',  [
             'leituras' => $leituras,
        ]);
    }

    public function geolocalizacao($contratoID)
    {
        $userActual = Auth::user();

        $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('contador',$contratoID)->first();

        return view('leitura.geolocalizacao',  [
             'cliente' => $cliente,
        ]);
    }

    public function localizarCasa($contratoID)
    {
        $userActual = Auth::user();

        $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('contador',$contratoID)->first();

        return view('leitura.localizarCasa',  [
             'cliente' => $cliente,
        ]);

    }

    public function edit($contratoID)
    {
        $userActual = Auth::user();

        $leitura = Leitura::where('empresa_id',$userActual->empresa_id)->where('id',$contratoID)->first();
        $ultimaLeitura = Leitura::where('furo_cliente_contrato_id', $leitura->furo_cliente_contrato_id)
                ->orderByDesc('id')             //Pegar O ultimo valor
                ->value('valor_leitura') ?? 0;  //Pagar o valor

        return view('leitura.edit',  [
             'leitura' => $leitura,
             'ultimaLeitura' => $ultimaLeitura,
        ]);
    }

    public function update(Request $request)
    {

        DB::beginTransaction();

        try {

            $userActual = Auth::user();

            // Cria Empresa e colocar Saldo de SMSCredito
            $furoClienteContrato = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('id',$request->input('furo_cliente_contrato'))->first();

            $consumo = $request->input('valor_leitura') - $request->input('ultima_leitura');
            $consumoMinimo = $consumo*$furoClienteContrato->contrato->consumo_minimo;

            $data = Carbon::now();
            $anoActual = Carbon::now()->year;
            $totalFatura = Fatura::where('empresa_id',$userActual->empresa_id)->get();
            $numeroFatura = str_pad(count($totalFatura) + 1, 7, '0', STR_PAD_LEFT).''.$anoActual;
            $valorAPagar = 0;

            $leitura = Leitura::where('id', $request->input('id'))->first();
            $leitura->valor_leitura = $request->input('valor_leitura');
            $leitura->data_leitura = Carbon::now();
            $leitura->consumo = $consumo;
            $leitura->estado_leitura = true;
            $leitura->leitura_feita_por = $userActual->id;
            $leitura->numero_factura = $numeroFatura;

            
            if($consumoMinimo < $consumo){
                $leitura->valor_a_pagar = $furoClienteContrato->contrato->consumo_minimo*$furoClienteContrato->contrato->valor;
                $valorAPagar = $furoClienteContrato->contrato->consumo_minimo*$furoClienteContrato->contrato->valor;
            }else{
                $leitura->valor_a_pagar = $consumo*$furoClienteContrato->contrato->valor;
                $valorAPagar = $consumo*$furoClienteContrato->contrato->valor;
            }
            

            if ($leitura->save()) {

                $factura = new Fatura();
                $factura->cliente_id = $furoClienteContrato->id;
                $factura->empresa_id = $userActual->empresa_id;
                $factura->numero_factura = $numeroFatura;
                $factura->data_emissao = $data;
                $factura->status = 'Pendente';
                $factura->tipo_pagamento_id = 2;
                $factura->contrato_id = $furoClienteContrato->id;
                $factura->valor = $valorAPagar;
                $factura->furo_id = $leitura->furo_id;

                if($factura->save()){

                    DB::commit();
                    return response()->json(['status' => 1, 'message' => 'Leitura Feito Com Sucesso']);

                }

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

    public function geolocalizacaoStore(Request $request)
    {

        DB::beginTransaction();

        try {

            $userActual = Auth::user();

            // Cria Empresa e colocar Saldo de SMSCredito
            $cliente = FuroClienteContrato::where('contador', $request->input('contador'))->first();
            $cliente->latitude = $request->input('latitude');
            $cliente->longitude = $request->input('longitude');

            if ($cliente->save()) {

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Actualizacao de Geolocalizacao Com Sucesso']);

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

    public function leituraContador($contratoID)
    {
        $userActual = Auth::user();

        $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('contador',$contratoID)->first();
        $ultimaLeitura = Leitura::where('furo_cliente_contrato_id', $cliente->id)
                        ->orderByDesc('id')             //Pegar O ultimo valor
                        ->value('valor_leitura') ?? 0;  //Pagar o valor

        return view('leitura.leitura',  [
             'cliente' => $cliente,
             'ultimaLeitura' => $ultimaLeitura,
        ]);
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            $userActual = Auth::user();

            // Cria Empresa e colocar Saldo de SMSCredito
            $cliente = FuroClienteContrato::where('contador', $request->input('contador'))->first();

            $leitura = Leitura::where('furo_cliente_contrato_id', $cliente->id)->first();
            $cliente->latitude = $request->input('latitude');
            $cliente->longitude = $request->input('longitude');

            if ($cliente->save()) {

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Actualizacao de Geolocalizacao Com Sucesso']);

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
