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
use App\Models\Mes;
use App\Models\BancoCarteira;
use App\Models\Furo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function index()
    {
        $userActual = Auth::user();

        $clientes = null;
        $furos = null;
        $provincias = Provincia::all();

        
        if (auth()->user()->hasRole('Admin')) {
            // usuário é admin
            $clientes = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->get();
            $furos = Furo::where('empresa_id',$userActual->empresa_id)->get();
        }

        if (auth()->user()->hasRole('SuperAdmin')) {
            // usuário é admin
            $clientes = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->get();
            $furos = Furo::where('empresa_id',$userActual->empresa_id)->get();
        }

        if (auth()->user()->hasRole('Leitura')) {
            // usuário é Leitura
            $clientes = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();
            $furos = Furo::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();
        }

        
        return view('clientes.index',  [
             'clientes' => $clientes,
             'furos' => $furos,
             'provincias' => $provincias,
        ]);
    }

    public function meuCliente()
    {
        $userActual = Auth::user();

        $clientes = null;
        $furos = null;
        $provincias = Provincia::all();
        
        if (auth()->user()->hasRole('Admin')) {
            // usuário é admin
            $clientes = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->get();
            $furos = Furo::where('empresa_id',$userActual->empresa_id)->get();
        }

        if (auth()->user()->hasRole('SuperAdmin')) {
            // usuário é admin
            $clientes = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->get();
            $furos = Furo::where('empresa_id',$userActual->empresa_id)->get();
        }

        if (auth()->user()->hasRole('Leitura')) {
            // usuário é Leitura
            $clientes = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();
            $furos = Furo::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();
        }

        
        return view('leitura.clientes',  [
             'clientes' => $clientes,
             'furos' => $furos,
             'provincias' => $provincias,
        ]);
    }

    public function create()
    {
        $userActual = Auth::user();

        $clientes = null;
        $furos = null;
        $provincias = Provincia::all();

        $mesAtual = Carbon::now()->month;
        $meses = Mes::where('numero','>',$mesAtual)->get();
        $contratos = Contrato::where('empresa_id',$userActual->empresa_id)->get();
        $formasPagamentos = FormaPagamento::all();
        $bancos = BancoCarteira::all();


        if (auth()->user()->hasRole('Admin')) {
            // usuário é admin
            $clientes = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->get();
            $furos = Furo::where('empresa_id',$userActual->empresa_id)->get();
        }

        if (auth()->user()->hasRole('SuperAdmin')) {
            // usuário é admin
            $clientes = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->get();
            $furos = Furo::where('empresa_id',$userActual->empresa_id)->get();
        }

        if (auth()->user()->hasRole('Leitura')) {
            // usuário é Leitura
            $clientes = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();
            $furos = Furo::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();
        }

        return view('clientes.create',  [
             'clientes' => $clientes,
             'furos' => $furos,
             'provincias' => $provincias,
             'meses' => $meses,
             'contratos' => $contratos,
             'formasPagamentos' => $formasPagamentos,
             'bancos' => $bancos,
        ]);
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            $userActual = Auth::user();
            $data = Carbon::now();
            $anoActual = Carbon::now()->year;
            $mesActual = Carbon::now()->month;
            $contrato = Contrato::where('id',$request->input('contrato'))->first();
            $ano = Ano::where('ano',$anoActual)->first();
            $mes = Mes::where('numero',$mesActual)->first();
            $totalFatura = Fatura::where('empresa_id',$userActual->empresa_id)->get();
            $totalRecibo = Recibo::where('empresa_id',$userActual->empresa_id)->get();

            $numeroFatura = str_pad(count($totalFatura) + 1, 7, '0', STR_PAD_LEFT).''.$anoActual;
            $numeroRecibo = str_pad(count($totalRecibo) + 1, 7, '0', STR_PAD_LEFT).''.$anoActual;

            $valorPago = $request->input('valor_pago');
            $valorAPagar = $contrato->valor_contrato;
            $estado = 'Pendente';
            $saldo = $valorAPagar - $valorPago;

            // Cria Empresa e colocar Saldo de SMSCredito
            $cliente = new Cliente();
            $cliente->nome = $request->input('cliente');
            $cliente->empresa_id = $userActual->empresa_id;
            $cliente->tipo_documento = $request->input('tipo_documento');
            $cliente->numero_documento = $request->input('numero_documento');
            $cliente->quarteirao = $request->input('quarteirao');
            $cliente->casa = $request->input('casa');
            $cliente->distrito_id = $request->input('distrito');
            $cliente->furo_id = $request->input('furo');

            if ($cliente->save()) {

                $furoClienteContrato = new FuroClienteContrato();
                $furoClienteContrato->contador = $request->input('numero_contador');
                $furoClienteContrato->saldo = $saldo;
                $furoClienteContrato->valor_pago = $valorPago;
                $furoClienteContrato->valor_a_pagar = $valorAPagar;

                if($valorPago == $valorAPagar){
                    $furoClienteContrato->estado_pagamento = 'Pago';
                    $estado = 'Pago';
                }elseif($valorPago == 0){
                    $furoClienteContrato->estado_pagamento = 'Pendente';
                    $estado = 'Pendente';
                }elseif($valorPago > 0 && $valorPago != $valorAPagar){
                    $furoClienteContrato->estado_pagamento = 'Parcial';
                    $estado = 'Parcial';
                }

                $furoClienteContrato->ultimo_pagamento = $data;
                $furoClienteContrato->bairro = $request->input('bairro');
                $furoClienteContrato->quarteirao = $request->input('quarteirao');
                $furoClienteContrato->casa = $request->input('casa');
                $furoClienteContrato->telefone_notificar = $request->input('telefone');
                $furoClienteContrato->contrato_id  = $request->input('contrato');
                $furoClienteContrato->distrito_id = $request->input('distrito');
                $furoClienteContrato->provincia_id = $request->input('provincia');
                $furoClienteContrato->ano_inicio_id = $ano->id;
                $furoClienteContrato->mes_inicio_id = $mes->id;
                $furoClienteContrato->furo_id = $request->input('furo');
                $furoClienteContrato->empresa_id = $userActual->empresa_id;
                $furoClienteContrato->cliente_id = $cliente->id;

                if($furoClienteContrato->save()){

                    $factura = new Fatura();
                    $factura->cliente_id = $furoClienteContrato->id;
                    $factura->empresa_id = $userActual->empresa_id;
                    $factura->numero_factura = $numeroFatura;
                    $factura->data_emissao = $data;
                    $factura->status = $estado;
                    $factura->tipo_pagamento_id = 1;
                    $factura->contrato_id = $furoClienteContrato->id;
                    $factura->tipo_pagamento_id = 1;
                    $factura->valor = $valorAPagar;
                    $factura->furo_id = $request->input('furo');

                    if($factura->save()){

                        if($valorPago > 0){

                            $pagamento = new Pagamento();
                            $pagamento->valor = $valorAPagar;
                            $pagamento->furo_id = $request->input('furo');
                            $pagamento->empresa_id = $userActual->empresa_id;
                            $pagamento->factura_id = $factura->id;
                            $pagamento->estado = $estado;
                            $pagamento->forma_pagamento_id = $request->input('forma_pagamento');
                            $pagamento->tipo_pagamento_id = 1;
                            $pagamento->descricao = $request->input('descricao');
                            $pagamento->tipo_banco = $request->input('banco');

                            if($pagamento->save()){

                                $recibo = new Recibo();
                                $recibo->cliente_id = $furoClienteContrato->id;
                                $recibo->empresa_id = $userActual->empresa_id;
                                $recibo->numero_factura = $numeroFatura;
                                $recibo->status = $estado;
                                $recibo->tipo_pagamento_id = 1;
                                $recibo->contrato_id = $furoClienteContrato->id;
                                $recibo->tipo_pagamento_id = 1;
                                $recibo->valor = $valorAPagar;
                                $recibo->pagamento_id = $pagamento->id;

                                if($recibo->save()){

                                    DB::commit();
                                    return response()->json(['status' => 1, 'message' => 'Contrato Registado Com Sucesso']);

                                }


                            }
                        

                        }else{

                            DB::commit();
                            return response()->json(['status' => 1, 'message' => 'Contrato Registado Com Sucesso']);

                        }

                    }

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
}
