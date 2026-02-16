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
use App\Models\Empresa;
use App\Models\Mensagem;
use App\Models\Ano;
use App\Models\Leitura;
use App\Models\SaldoSMS;
use App\Models\Mes;
use App\Models\BancoCarteira;
use App\Models\Furo;
use App\Services\SMSService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeituraController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Mês anterior
        $mesAnterior = Carbon::now()->subMonth()->month;

        // Query base
        $leituras = Leitura::where('empresa_id', $user->empresa_id)->where('furo_id', $user->furo_id)->where('mes_id', $mesAnterior)->get();


        return view('leitura.index', compact('leituras'));
    }

    public function geolocalizacao($contratoID)
    {
        $userActual = Auth::user();

        $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('codigo',$contratoID)->first();

        return view('leitura.geolocalizacao',  [
             'cliente' => $cliente,
        ]);
    }

    
    public function todasLeituras()
    {
        $userActual = Auth::user();
        $mesAtual = Carbon::now()->month;

        $leituras = Leitura::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();

        return view('leitura.index',  [
             'leituras' => $leituras,
        ]);

    }

    public function pendentes()
    {
        $userActual = Auth::user();
        $mesAtual = Carbon::now()->month;

        $leituras = Leitura::where('empresa_id',$userActual->empresa_id)->where('estado_leitura',0)->where('furo_id',$userActual->furo_id)->where('mes_id', Carbon::now()->subMonth()->month)->get();

        return view('leitura.index',  [
             'leituras' => $leituras,
        ]);

    }

    public function registarLeitura()
    {
        $userActual = Auth::user();

        $leituras = Leitura::where('empresa_id',$userActual->empresa_id)->where('mes_id',Carbon::now()->subMonth()->month)->where('furo_id',$userActual->furo_id)->get();
        $empresa = Empresa::where('id',$userActual->empresa_id)->first();
        $mesLeitura = Mes::where('numero',Carbon::now()->subMonth()->month)->first(); 

        return view('leitura.registarLeitura',  [
             'leituras' => $leituras,
             'mesLeitura' => $mesLeitura,
        ]);
    }

    public function leituraFazer()
    {
        $userActual = Auth::user();

        $leituras = Leitura::where('empresa_id',$userActual->empresa_id)->where('mes_id',Carbon::now()->subMonth()->month)->where('furo_id',$userActual->furo_id)->get();
        $empresa = Empresa::where('id',$userActual->empresa_id)->first();
        $mesLeitura = Mes::where('numero',Carbon::now()->subMonth()->month)->first();


        $pdf = \PDF::loadView('leitura.fazerLeitura', [
             'leituras' => $leituras,
             'empresa' => $empresa,
             'mesLeitura' => $mesLeitura,
        ])->setPaper('a4', 'Portrait'); 

        $fikeName = 'factura-'.$mesLeitura->nome;

        return $pdf->stream($fikeName.'.pdf');
    }

    public function fatura($leituraID)
    {
        $userActual = Auth::user();

        $leitura = Leitura::where('empresa_id',$userActual->empresa_id)->where('id',$leituraID)->first();
        $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('id',$leitura->furo_cliente_contrato_id)->first();
        $fatura = Fatura::where('empresa_id',$userActual->empresa_id)->where('numero_factura',$leitura->numero_factura)->first();
        $empresa = Empresa::where('id',$userActual->empresa_id)->first();


        $pdf = \PDF::loadView('leitura.factura', [
             'cliente' => $cliente,
             'leitura' => $leitura,
             'fatura' => $fatura,
             'empresa' => $empresa,
        ])->setPaper([0, 0, 200, 1000]); 

        $fikeName = 'factura-'.$leitura->numero_factura;

        return $pdf->stream($fikeName.'.pdf');

    }

    public function facturaLeitura($codigo)
    {
        $userActual = Auth::user();

        $empresa = Empresa::where('id',$userActual->empresa_id)->first();

        $leitura = Leitura::where('empresa_id',$userActual->empresa_id)->where('estado_leitura',1)->where('id',$codigo)->first();

        $pdf = \PDF::loadView('leitura.factura1', [
             'leitura' => $leitura,
             'empresa' => $empresa,
        ])->setPaper('a4', 'Portrait');

        $fikeName = 'Todas Facturas - '.$empresa->nome;

        return $pdf->stream($fikeName.'.pdf');

    }

    public function facturasTodos()
    {
        $userActual = Auth::user();
        $mesAtual = Carbon::now()->subMonth()->month;

        $empresa = Empresa::where('id',$userActual->empresa_id)->first();

        // usuário é Leitura
        $leituras = Leitura::where('empresa_id',$userActual->empresa_id)->where('estado_leitura',1)->where('furo_id',$userActual->furo_id)->where('mes_id',$mesAtual)->get();

        $pdf = \PDF::loadView('leitura.facturasTodos', [
             'leituras' => $leituras,
             'empresa' => $empresa,
        ])->setPaper('a4', 'Portrait');

        $fikeName = 'Todas Facturas - '.$empresa->nome;

        return $pdf->stream($fikeName.'.pdf');
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
        $mesAtual = Carbon::now()->month;

        $leitura = Leitura::where('empresa_id',$userActual->empresa_id)->where('id',$contratoID)->first();
        $ultimaLeitura = Leitura::where('furo_cliente_contrato_id', $leitura->furo_cliente_contrato_id)
                ->where('mes_id','<>',Carbon::now()->subMonth()->month)
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
            $consumoMinimo = $furoClienteContrato->contrato->consumo_minimo;

            $data = Carbon::now();
            $anoActual = Carbon::now()->year;
            $totalFatura = Fatura::where('empresa_id',$userActual->empresa_id)->get();
            $numeroFatura = str_pad(count($totalFatura) + 1, 7, '0', STR_PAD_LEFT).''.$anoActual;
            $valorAPagar = 0;

            $leitura = Leitura::where('id', $request->input('id'))->first();
            $empresa = Empresa::where('id',$userActual->empresa_id)->first();

            if($leitura->estado_leitura==0){

                $credito = SaldoSMS::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->first();

                if($credito->saldo_sistema - $empresa->valor_por_cliente >= 0){

                    $credito->saldo_sistema = $credito->saldo_sistema - $empresa->valor_por_cliente;
                    $credito->save();

                    $leitura->credito = $empresa->valor_por_cliente;
                    $leitura->credito_saldo = $credito->saldo_sistema;

                }else{

                    DB::rollBack();
                    return response()->json(['status' => 0, 'message' => 'Saldo do Sistema Insuficiente, Por favor recarrega o saldo de sistema']);
                }


            }

            $leitura->valor_leitura = $request->input('valor_leitura');
            $leitura->data_leitura = Carbon::now();
            $leitura->consumo = $consumo;
            $leitura->estado_leitura = true;
            $leitura->leitura_feita_por = $userActual->id;
            $leitura->numero_factura = $numeroFatura;
            $leitura->prazo_pagamento = Carbon::now()->setDay($furoClienteContrato->data_multa);

            
            if($consumo < $consumoMinimo){
                $leitura->valor_a_pagar = $furoClienteContrato->contrato->consumo_minimo*$furoClienteContrato->contrato->valor;
                $valorAPagar = $furoClienteContrato->contrato->consumo_minimo*$furoClienteContrato->contrato->valor;
            }else{
                $leitura->valor_a_pagar = $consumo*$furoClienteContrato->contrato->valor;
                $valorAPagar = $consumo*$furoClienteContrato->contrato->valor;
            }
            

            if ($leitura->save()) {

                //Criacao de Factura
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
                $factura->leitura_id = $leitura->id;

                $valorFormatado     = number_format($valorAPagar, 2, ',', '.');
                $dividaFormatada    = number_format($furoClienteContrato->divida, 2, ',', '.');
                $total              = $valorAPagar + $furoClienteContrato->divida;
                $totalFormatado     = number_format($total, 2, ',', '.');

                $smsDescricao = "Caro(a) {$furoClienteContrato->cliente->nome}, "
                                . "Factura {$numeroFatura} do mês {$leitura->mes->nome}-{$leitura->ano->ano}. "
                                . "Consumo: {$consumo}m3. "
                                . "Valor: {$valorFormatado} MT. "
                                . "Dívida: {$dividaFormatada} MT. "
                                . "Total a pagar: {$totalFormatado} MT.";

                //Gerar SMS de Factura
                $sms = new Mensagem();
                $sms->descricao = $smsDescricao;
                $sms->telefone = $furoClienteContrato->telefone_notificar;
                $sms->nome = $furoClienteContrato->cliente->nome;
                $sms->qtd = SMSService::quantidadeSMS($smsDescricao);
                $sms->credito = SMSService::quantidadeSMS($smsDescricao)*1.8;
                $sms->custo_real = SMSService::quantidadeSMS($smsDescricao)*1.2;
                $sms->empresa_id = $userActual->empresa_id;
                $sms->furo_id = $leitura->furo_id;
                $sms->data_envio = $data;

                if($factura->save() && $sms->save()){

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

    public function updateTodos(Request $request)
    {
        DB::beginTransaction();

        try {

            $userActual = Auth::user();
            $empresa = Empresa::where('id', $userActual->empresa_id)->first();

            if (!$request->has('leituras')) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Nenhuma leitura foi preenchida.'
                ]);
            }

            foreach ($request->leituras as $item) {

                $leitura = Leitura::where('id', $item['id'])->first();

                if (!$leitura || $leitura->estado_leitura == 1) {
                    continue; // ignora já feitas
                }


                $furoClienteContrato = FuroClienteContrato::where('id', $leitura->furo_cliente_contrato_id)
                    ->where('empresa_id', $userActual->empresa_id)
                    ->first();

                if (!$furoClienteContrato) {
                    continue;
                }

                $ultimaLeitura = $furoClienteContrato->ultima_leitura;
                $novaLeitura   = $item['valor'];

                if ($novaLeitura < $ultimaLeitura) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 0,
                        'message' => 'Leitura actual não pode ser menor que a anterior.'
                    ]);
                }

                // =========================
                // CALCULAR CONSUMO
                // =========================
                $consumo = $novaLeitura - $ultimaLeitura;
                $consumoMinimo = $furoClienteContrato->contrato->consumo_minimo;

                // =========================
                // VALIDAR SALDO SISTEMA
                // =========================
                $saldo = SaldoSMS::where('empresa_id', $userActual->empresa_id)
                    ->where('furo_id', $userActual->furo_id)
                    ->first();

                if ($saldo->saldo_sistema < $empresa->valor_por_cliente) {

                    DB::rollBack();
                    return response()->json([
                        'status' => 0,
                        'message' => 'Saldo do sistema insuficiente. Recarregue para continuar.'
                    ]);
                }

                // Debitar saldo
                $saldo->saldo_sistema -= $empresa->valor_por_cliente;
                $saldo->save();

                // =========================
                // CALCULAR VALOR A PAGAR
                // =========================
                if ($consumo < $consumoMinimo) {
                    $valorAPagar = $consumoMinimo * $furoClienteContrato->contrato->valor;
                } else {
                    $valorAPagar = $consumo * $furoClienteContrato->contrato->valor;
                }

                // =========================
                // GERAR NÚMERO FACTURA
                // =========================
                $anoActual = now()->year;
                $numeroFatura = str_pad(Fatura::max('id') + 1, 7, '0', STR_PAD_LEFT) . $anoActual;

                // =========================
                // ATUALIZAR LEITURA
                // =========================
                $leitura->valor_leitura   = $novaLeitura;
                $leitura->data_leitura    = $item['data'];
                $leitura->consumo         = $consumo;
                $leitura->estado_leitura  = 1;
                $leitura->leitura_feita_por = $userActual->id;
                $leitura->valor_a_pagar   = $valorAPagar;
                $leitura->numero_factura  = $numeroFatura;
                $leitura->prazo_pagamento = now()->setDay($furoClienteContrato->data_multa);
                $leitura->credito         = $empresa->valor_por_cliente;
                $leitura->credito_saldo   = $saldo->saldo_sistema;

                $leitura->save();

                // =========================
                // CRIAR FACTURA
                // =========================
                $factura = new Fatura();
                $factura->cliente_id      = $furoClienteContrato->id;
                $factura->empresa_id      = $userActual->empresa_id;
                $factura->numero_factura  = $numeroFatura;
                $factura->data_emissao    = now();
                $factura->status          = 'Pendente';
                $factura->tipo_pagamento_id = 2;
                $factura->contrato_id     = $furoClienteContrato->contrato_id;
                $factura->valor           = $valorAPagar;
                $factura->furo_id         = $leitura->furo_id;
                $factura->leitura_id      = $leitura->id;

                $factura->save();

                // =========================
                // GERAR SMS
                // =========================
                $valorFormatado = number_format($valorAPagar, 2, ',', '.');

                $smsDescricao = "Caro(a) {$furoClienteContrato->cliente->nome}, "
                    . "Factura {$numeroFatura} do mês {$leitura->mes->nome}-{$leitura->ano->ano}. "
                    . "Consumo: {$consumo}m3. "
                    . "Valor: {$valorFormatado} MT.";

                $sms = new Mensagem();
                $sms->descricao  = $smsDescricao;
                $sms->telefone   = $furoClienteContrato->telefone_notificar;
                $sms->nome       = $furoClienteContrato->cliente->nome;
                $sms->qtd        = SMSService::quantidadeSMS($smsDescricao);
                $sms->credito    = $sms->qtd * 1.85;
                $sms->custo_real = $sms->qtd * 1.35;
                $sms->empresa_id = $userActual->empresa_id;
                $sms->furo_id    = $leitura->furo_id;
                $sms->data_envio = now();

                $sms->save();
            }

            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Leituras registadas com sucesso.'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
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
            $cliente->localizacao_activa = true;

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

    //Extracto de pagamentos
    public function extracto($codigo)
    {
        $userActual = Auth::user();

        $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('codigo',$codigo)->first();
        $leituras = Leitura::where('furo_cliente_contrato_id', $cliente->id)->where('estado_leitura',1)->where('furo_id',$userActual->furo_id)->get();
        $empresa = Empresa::where('id',$userActual->empresa_id)->first();

        $pdf = \PDF::loadView('pagamento.pdfExtracto', [
             'cliente' => $cliente,
             'leituras' => $leituras,
             'empresa' => $empresa,
        ])->setPaper('a4', 'Portrait');

        $fikeName = 'Extracto - '.$cliente->cliente->nome;

        return $pdf->stream($fikeName.'.pdf');

    }

    public function leituraContador($contratoID)
    {
        $userActual = Auth::user();

        $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('codigo',$contratoID)->first();
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
