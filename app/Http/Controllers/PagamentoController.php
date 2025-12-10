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
use App\Models\Mensagem;
use App\Models\Leitura;
use App\Models\Mes;
use App\Services\SMSService;
use App\Models\BancoCarteira;
use App\Models\Furo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class PagamentoController extends Controller
{
    public function index()
    {
        $userActual = Auth::user();

        $leituras = null;
        $furos = null;
        $provincias = Provincia::all();
        
        if (auth()->user()->hasRole('Admin')) {
            // usuário é admin
            $leituras = Leitura::where('empresa_id',$userActual->empresa_id)->where('estado_leitura',1)->get();
        }

        if (auth()->user()->hasRole('SuperAdmin')) {
            // usuário é admin
            $leituras = Leitura::where('empresa_id',$userActual->empresa_id)->where('estado_leitura',1)->get();
        }

        if (auth()->user()->hasRole('Leitura')) {
            // usuário é Leitura
            $leituras = Leitura::where('empresa_id',$userActual->empresa_id)->where('estado_leitura',1)->where('furo_id',$userActual->furo_id)->get();
        }
        
        return view('pagamento.index',  [
             'leituras' => $leituras,
        ]);
    }

    public function reciboLeitura($contratoID)
    {
        $userActual = Auth::user();

        $leitura = Leitura::where('empresa_id',$userActual->empresa_id)->where('id',$contratoID)->first();
        $empresa = Empresa::where('id',$userActual->empresa_id)->first();
        $pagamentos = Pagamento::where('leitura_id',$leitura->id)->get();

        $pdf = \PDF::loadView('pagamento.recibo', [
             'leitura' => $leitura,
             'empresa' => $empresa,
             'pagamentos' => $pagamentos,
        ])->setPaper('a4', 'Portrait');

        $fikeName = 'Recibo - '.$leitura->numero_factura;

        return $pdf->stream($fikeName.'.pdf');

    }

    public function show($contratoID)
    {
        $userActual = Auth::user();

        $leitura = Leitura::where('empresa_id',$userActual->empresa_id)->where('id',$contratoID)->first();
        $formasPagamentos = FormaPagamento::all();
        $bancos = BancoCarteira::all();
        $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('contador',$leitura->furoClienteContrato->contador)->first();

        $valor = Leitura::where('furo_cliente_contrato_id', $leitura->furo_cliente_contrato_id)
                ->where('estado_leitura', 1)
                ->orderByDesc('id')             //Pegar O ultimo valor
                ->value('valor_a_pagar') ?? 0;  //Pagar o valor

        $totalAPagar = ($leitura->valor_a_pagar + $leitura->furoClienteContrato->divida) + (($leitura->valor_a_pagar + $leitura->furoClienteContrato->divida) * $leitura->multa / 100); 
        $saldoDisponivel = $leitura->furoClienteContrato->saldo;
        
        // saldo que pode usar = o menor entre saldo e total a pagar
        $saldoAUsar = min($saldoDisponivel, $totalAPagar);

        return view('pagamento.pagamento',  [
             'leitura' => $leitura,
             'valor' => $valor,
             'cliente' => $cliente,
             'formasPagamentos' => $formasPagamentos,
             'bancos' => $bancos,
             'saldoAUsar' => $saldoAUsar,
        ]);
    }

    public function showParcial($contratoID)
    {

        $userActual = Auth::user();

        $leitura = Leitura::where('empresa_id',$userActual->empresa_id)->where('id',$contratoID)->first();
        $formasPagamentos = FormaPagamento::all();
        $bancos = BancoCarteira::all();
        $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('contador',$leitura->furoClienteContrato->contador)->first();

        $valor = Leitura::where('furo_cliente_contrato_id', $leitura->furo_cliente_contrato_id)
                ->where('estado_leitura', 1)
                ->orderByDesc('id')             //Pegar O ultimo valor
                ->value('valor_a_pagar') ?? 0;  //Pagar o valor

        return view('pagamento.pagamentoParcial',  [
             'leitura' => $leitura,
             'valor' => $valor,
             'cliente' => $cliente,
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
            $ano = Ano::where('ano',$anoActual)->first();
            $mes = Mes::where('numero',$mesActual)->first();
            $fatura = Fatura::where('empresa_id',$userActual->empresa_id)->where('furo_id',$request->input('furo'))->where('numero_factura',$request->input('fatura'))->first();
            $totalRecibo = Recibo::where('empresa_id',$userActual->empresa_id)->get();

            $numeroRecibo = str_pad(count($totalRecibo) + 1, 7, '0', STR_PAD_LEFT).''.$anoActual;

            $leitura = Leitura::where('empresa_id',$userActual->empresa_id)->where('id',$request->input('id'))->first();
            $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('contador',$leitura->furoClienteContrato->contador)->first();

            $valorPago = $request->input('valor_pago');
            $saldoAUsar = $request->input('saldo');
            $valorTotal = $request->input('valor_total');
            $multa = $request->input('multa');
            $consumo = $request->input('consumo');
            $novaDivida = $request->input('nova_divida');
            $novaDivida = substr($novaDivida, 0, -3);
            $estado = null;

            if($novaDivida==0){

                $estado = "Pago";

            }elseif($novaDivida>0){

                $estado = "Parcial";
                $cliente->divida = $novaDivida;
                $cliente->save();
            }

            $leitura->estado_pagamento = $estado;
            $leitura->valor_pago = $valorPago;
            $leitura->saldo = $consumo - $valorPago;
            $leitura->saldo_usado = $saldoAUsar;
            $leitura->multa = $multa;
            $leitura->data_pagamento = $data;
            $leitura->divida_anterior = $novaDivida;
            $leitura->save();

            $pagamento = new Pagamento();
            $pagamento->valor = $valorPago;
            $pagamento->furo_id = $request->input('furo');
            $pagamento->empresa_id = $userActual->empresa_id;
            $pagamento->leitura_id = $leitura->id;
            $pagamento->factura_id = $fatura->id;
            $pagamento->estado = $estado;
            $pagamento->forma_pagamento_id = $request->input('forma_pagamento');
            $pagamento->tipo_pagamento_id = 2;
            $pagamento->descricao = $request->input('descricao');
            $pagamento->tipo_banco = $request->input('banco_carteira');

            if($saldoAUsar>0 && $request->input('forma_pagamento')<4){
                    return response()->json(['status' => 0, 'message' => 'O Metodo de pagamento deve ser outro']);
            }

            if($pagamento->save()){

                $recibo = new Recibo();
                $recibo->cliente_id = $leitura->furoClienteContrato->id;
                $recibo->empresa_id = $userActual->empresa_id;
                $recibo->numero_factura = $request->input('fatura');
                $recibo->status = $estado;
                $recibo->tipo_pagamento_id = 1;
                $recibo->factura_id = $fatura->id;
                $recibo->valor = $valorPago;
                $recibo->leitura_id = $leitura->id;
                $recibo->pagamento_id = $pagamento->id;

                $valorFormatado     = number_format($valorPago, 2, ',', '.');
                $dividaFormatada    = number_format($novaDivida, 2, ',', '.');
                $total              = $valorPago + $novaDivida;
                $totalFormatado     = number_format($total, 2, ',', '.');

                $smsDescricao = "Caro(a) {$cliente->cliente->nome}, "
                                . "Recibo {$numeroRecibo} do mês {$leitura->mes->nome}-{$leitura->ano->ano}. "
                                . "Consumo: {$consumo}m3. "
                                . "Valor Pago: {$valorFormatado} MT. "
                                . "Dívida: {$dividaFormatada} MT. ";

                //Gerar SMS de Recibo
                $sms = new Mensagem();
                $sms->descricao = $smsDescricao;
                $sms->telefone = $cliente->telefone_notificar;
                $sms->nome = $cliente->cliente->nome;
                $sms->qtd = SMSService::quantidadeSMS($smsDescricao);
                $sms->credito = SMSService::quantidadeSMS($smsDescricao)*1.8;
                $sms->custo_real = SMSService::quantidadeSMS($smsDescricao)*1.2;
                $sms->empresa_id = $userActual->empresa_id;
                $sms->furo_id = $leitura->furo_id;
                $sms->data_envio = $data;

                if($recibo->save() && $sms->save()){

                    DB::commit();
                    return response()->json(['status' => 1, 'message' => 'Pagamento Efectuado Com Sucesso']);
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

    public function storeParcial(Request $request)
    {
        DB::beginTransaction();

        try {

            $userActual = Auth::user();

            $data = Carbon::now();
            $anoActual = Carbon::now()->year;
            $mesActual = Carbon::now()->month;
            $ano = Ano::where('ano',$anoActual)->first();
            $mes = Mes::where('numero',$mesActual)->first();
            $fatura = Fatura::where('empresa_id',$userActual->empresa_id)->where('furo_id',$request->input('furo'))->where('numero_factura',$request->input('fatura'))->first();
            $totalRecibo = Recibo::where('empresa_id',$userActual->empresa_id)->get();

            $numeroRecibo = str_pad(count($totalRecibo) + 1, 7, '0', STR_PAD_LEFT).''.$anoActual;

            $leitura = Leitura::where('empresa_id',$userActual->empresa_id)->where('id',$request->input('id'))->first();
            $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('contador',$leitura->furoClienteContrato->contador)->first();

            $valorPago = $request->input('valor_pago');
            $valorTotal = $request->input('valor_total');
            $novaDivida = $request->input('nova_divida');
            $multa = $request->input('multa');
            $novaDivida = substr($novaDivida, 0, -3);
            $estado = null;

            if($novaDivida==0){

                $estado = "Pago";
                $cliente->divida = $novaDivida;
                $cliente->save();

            }elseif($novaDivida>0){

                $estado = "Parcial";
                $cliente->divida = $novaDivida;
                $cliente->save();
            }

            $leitura->estado_pagamento = $estado;
            $leitura->multa = $multa;
            $leitura->valor_pago = $leitura->valor_pago + $valorPago;
            $leitura->saldo = $novaDivida;
            $leitura->save();

            $pagamento = new Pagamento();
            $pagamento->valor = $valorPago;
            $pagamento->furo_id = $request->input('furo');
            $pagamento->empresa_id = $userActual->empresa_id;
            $pagamento->leitura_id = $leitura->id;
            $pagamento->factura_id = $fatura->id;
            $pagamento->estado = $estado;
            $pagamento->forma_pagamento_id = $request->input('forma_pagamento');
            $pagamento->tipo_pagamento_id = 2;
            $pagamento->descricao = $request->input('descricao');
            $pagamento->tipo_banco = $request->input('banco_carteira');

            if($pagamento->save()){

                $recibo = new Recibo();
                $recibo->cliente_id = $leitura->furoClienteContrato->id;
                $recibo->empresa_id = $userActual->empresa_id;
                $recibo->numero_factura = $request->input('fatura');
                $recibo->status = $estado;
                $recibo->tipo_pagamento_id = 1;
                $recibo->factura_id = $fatura->id;
                $recibo->valor = $valorPago;
                $recibo->pagamento_id = $pagamento->id;

                $valorFormatado     = number_format($valorPago, 2, ',', '.');
                $dividaFormatada    = number_format($novaDivida, 2, ',', '.');
                $total              = $valorPago + $novaDivida;
                $totalFormatado     = number_format($total, 2, ',', '.');

                $smsDescricao = "Caro(a) {$cliente->cliente->nome}, "
                                . "Recibo {$numeroRecibo} do mês {$leitura->mes->nome}-{$leitura->ano->ano}. "
                                . "Consumo: {$leitura->consumo}m3. "
                                . "Valor Pago: {$valorFormatado} MT. "
                                . "Dívida: {$dividaFormatada} MT. ";

                //Gerar SMS de Recibo
                $sms = new Mensagem();
                $sms->descricao = $smsDescricao;
                $sms->telefone = $cliente->telefone_notificar;
                $sms->nome = $cliente->cliente->nome;
                $sms->qtd = SMSService::quantidadeSMS($smsDescricao);
                $sms->credito = SMSService::quantidadeSMS($smsDescricao)*1.8;
                $sms->custo_real = SMSService::quantidadeSMS($smsDescricao)*1.2;
                $sms->empresa_id = $userActual->empresa_id;
                $sms->furo_id = $leitura->furo_id;
                $sms->data_envio = $data;

                if($recibo->save() && $sms->save()){

                    DB::commit();
                    return response()->json(['status' => 1, 'message' => 'Pagamento Efectuado Com Sucesso']);
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

    public function fatura()
    {
        //$connector = new WindowsPrintConnector("Goojprt PT-210");
        $connector = new FilePrintConnector("LPT1"); // ou COM3
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("ÁGUA MUNICIPAL\n");
        $printer->text("Recibo de Pagamento\n");
        $printer->feed();

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Cliente: \n");
        $printer->text("Contador Nº: \n");
        $printer->text("Mês: \n");
        $printer->text("------------------------------\n");
        $printer->text("Água:  MT\n");
        $printer->text("Dívida:  MT\n");
        $printer->text("Multa:  MT\n");
        $printer->text("------------------------------\n");
        $printer->text("TOTAL:  MT\n");
        $printer->feed(2);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Obrigado pela preferência!\n");

        $printer->cut();
        $printer->close();

        return back()->with('success', 'Fatura impressa com sucesso!');

    }
}
