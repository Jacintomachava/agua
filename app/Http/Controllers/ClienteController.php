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
use App\Models\Distrito;
use App\Models\BancoCarteira;
use App\Models\Furo;
use App\Models\Empresa;
use App\Models\Tubagem;
use App\Models\Mensagem;
use App\Models\Nacionalidade;
use App\Models\Leitura;
use App\Models\User;
use Carbon\Carbon;
use App\Services\SMSService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $provincias = Provincia::all();
        $nacionalidades = Nacionalidade::all();

        // Dados
        $clientes = FuroClienteContrato::where('empresa_id', $user->empresa_id)->where('furo_id', $user->furo_id)->get();
        $furos = Furo::where('empresa_id', $user->empresa_id)->get();

        return view('clientes.index', compact(
            'clientes',
            'furos',
            'provincias',
            'nacionalidades'
        ));
    }

    public function meuCliente()
    {
        $userActual = Auth::user();

        $provincias = Provincia::all();

        // usuário é Leitura
        $clientes = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();
        $furos = Furo::where('empresa_id',$userActual->empresa_id)->get();
        
        return view('leitura.clientes',  [
             'clientes' => $clientes,
             'furos' => $furos,
             'provincias' => $provincias,
        ]);
    }

    public function show($codigo)
    {
        $userActual = Auth::user();

        $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('codigo', $codigo)->first();
        $leituras = Leitura::where('furo_cliente_contrato_id', $cliente->id)->where('estado_leitura',1)->where('furo_id',$userActual->furo_id)->get();
        $recibos = Recibo::where('cliente_id',$cliente->id)->where('furo_id',$userActual->furo_id)->get();
        $mensagens = Mensagem::where('telefone',$cliente->telefone_notificar)->where('furo_id',$userActual->furo_id)->get();

        return view('clientes.show',  [
             'cliente' => $cliente,
             'leituras' => $leituras,
             'recibos' => $recibos,
             'mensagens' => $mensagens,
        ]);
    }

    public function cortar($codigo)
    {

        $userActual = Auth::user();

        $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('codigo', $codigo)->first();
        $valorPendente = Leitura::where('furo_cliente_contrato_id', $cliente->id)
                ->where('estado_pagamento','Pendente')            //Pegar O ultimo valor
                ->value('valor_a_pagar') ?? 0;  //Pagar o valor

        return view('clientes.cortar',  [
             'cliente' => $cliente,
             'valorPendente' => $valorPendente,
        ]);
    }

    public function mapa()
    {
        $userActual = Auth::user();

        $tubagens = Tubagem::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();
        $clientes = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();

        return view('clientes.mapa',  [
             'clientes' => $clientes,
             'tubagens' => $tubagens,
        ]);

    }

    public function PDFCliente()
    {
        $userActual = Auth::user();

        $clientes = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->get();
        $empresa = Empresa::where('id',$userActual->empresa_id)->first();

        $pdf = \PDF::loadView('clientes.pdf', [
             'clientes' => $clientes,
             'empresa' => $empresa,
        ])->setPaper('a4', 'Portrait');

        $fikeName = 'Clientes-'.$empresa->nome;

        return $pdf->stream($fikeName.'.pdf');
    }

    public function ligar($codigo)
    {

        $userActual = Auth::user();

        $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('codigo', $codigo)->first();
        $valorPendente = Leitura::where('furo_cliente_contrato_id', $cliente->id)
                ->where('estado_pagamento','Pendente')            //Pegar O ultimo valor
                ->value('valor_a_pagar') ?? 0;  //Pagar o valor

        return view('clientes.ligar',  [
             'cliente' => $cliente,
             'valorPendente' => $valorPendente,
        ]);
    }

    public function registarLigar(Request $request)
    {

        DB::beginTransaction();

        try {

            $userActual = Auth::user();

            $taxa = $request->input('taxa');
            $total = $request->input('total');
            $divida = $request->input('divida');

            $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('codigo', $request->input('codigo'))->first();
            $novaDivida = $cliente->divida-$divida;
            $cliente->valor_reinstalacao = $taxa;
            $cliente->divida = $novaDivida;
            $cliente->ligacao_activa = true;

            $smsDescricao = "Caro(a) {$cliente->cliente->nome}, "
                . "a ligacao foi reestabelecida com a nova divida de: {$novaDivida} e foi cobrada a taxa de {$taxa} total pago {$total}";

            //Gerar SMS de Factura
            $sms = new Mensagem();
            $sms->descricao = $smsDescricao;
            $sms->telefone = $cliente->telefone_notificar;
            $sms->nome = $cliente->cliente->nome;
            $sms->qtd = SMSService::quantidadeSMS($smsDescricao);
            $sms->credito = SMSService::quantidadeSMS($smsDescricao)*1.8;
            $sms->custo_real = SMSService::quantidadeSMS($smsDescricao)*1.2;
            $sms->empresa_id = $userActual->empresa_id;
            $sms->furo_id = $cliente->furo_id;
            $sms->data_envio = Carbon::now();

            if($cliente->save() && $sms->save()){

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Corte Registado Com Sucesso']);
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

    public function registarCortar(Request $request)
    {
        DB::beginTransaction();

        try {

            $userActual = Auth::user();

            $divida = $request->input('divida');
            $motivo = $request->input('motivo');

            $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('codigo', $request->input('codigo'))->first();
            $cliente->data_corte = $request->input('data');
            $cliente->motivo_corte = $motivo;
            $cliente->divida = $divida;
            $cliente->ligacao_activa = false;

            Leitura::where('empresa_id', $userActual->empresa_id)
                    ->where('furo_cliente_contrato_id', $cliente->id)
                    ->where('estado_pagamento', 'Pendente')
                    ->update([
                        'estado' => 'Divida'
                    ]);

            $smsDescricao = "Caro(a) {$cliente->cliente->nome}, "
                . "foi cortado o fornecimento de agua com o saldo da divida {$divida}MT "
                . "por motivo de {$motivo}";

            //Gerar SMS de Factura
            $sms = new Mensagem();
            $sms->descricao = $smsDescricao;
            $sms->telefone = $cliente->telefone_notificar;
            $sms->nome = $cliente->cliente->nome;
            $sms->qtd = SMSService::quantidadeSMS($smsDescricao);
            $sms->credito = SMSService::quantidadeSMS($smsDescricao)*1.8;
            $sms->custo_real = SMSService::quantidadeSMS($smsDescricao)*1.2;
            $sms->empresa_id = $userActual->empresa_id;
            $sms->furo_id = $cliente->furo_id;
            $sms->data_envio = Carbon::now();

            if($cliente->save() && $sms->save()){

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Corte Registado Com Sucesso']);
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

    public function edit($codigo)
    {
        $userActual = Auth::user();
        $furos = null;
        $provincias = Provincia::all();
        $distritos = Distrito::all();

        $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('codigo', $codigo)->first();
        $mesAtual = Carbon::now()->month;
        $meses = Mes::where('numero','>',$mesAtual)->get();
        $contratos = Contrato::where('empresa_id',$userActual->empresa_id)->get();
        $formasPagamentos = FormaPagamento::all();
        $bancos = BancoCarteira::all();

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

        return view('clientes.edit',  [
             'cliente' => $cliente,
             'furos' => $furos,
             'provincias' => $provincias,
             'distritos' => $distritos,
             'meses' => $meses,
             'contratos' => $contratos,
             'formasPagamentos' => $formasPagamentos,
             'bancos' => $bancos,
        ]);
    }

    public function create()
    {
        $user = Auth::user();

        // Coleções vazias (evita null)
        $clientes = collect();
        $furos = collect();

        // Dados fixos
        $provincias = Provincia::all();
        $formasPagamentos = FormaPagamento::all();
        $bancos = BancoCarteira::all();
        $nacionalidades = Nacionalidade::all();

        // Meses futuros
        $mesAtual = Carbon::now()->month;
        $meses = Mes::where('numero', '>', $mesAtual)->get();

        // Contratos da empresa
        $contratos = Contrato::where('empresa_id', $user->empresa_id)->get();

        // Queries base
        $clientesQuery = FuroClienteContrato::where('empresa_id', $user->empresa_id);
        $furosQuery = Furo::where('empresa_id', $user->empresa_id);

        // Perfil Leitura → apenas seu furo
        if ($user->hasRole('Leitura')) {
            $clientesQuery->where('furo_id', $user->furo_id);
            $furosQuery->where('id', $user->furo_id);
        }

        // Admin e SuperAdmin → veem tudo da empresa
        // (nenhuma restrição extra)

        $clientes = $clientesQuery->get();
        $furos = $furosQuery->get();

        return view('clientes.create', compact(
            'clientes',
            'furos',
            'provincias',
            'meses',
            'contratos',
            'formasPagamentos',
            'bancos',
            'nacionalidades'
        ));
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
            $year_last_two_digits = substr($anoActual, -2);
            //Factura e Recibo
            $totalFatura = Fatura::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();
            $totalRecibo = Recibo::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();
            $totalCliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->get();
            $totalUser = User::where('empresa_id',$userActual->empresa_id)->get();

            $numeroFatura = str_pad(count($totalFatura) + 1, 7, '0', STR_PAD_LEFT).'-'.$anoActual;
            $numeroRecibo = str_pad(count($totalRecibo) + 1, 7, '0', STR_PAD_LEFT).'-'.$anoActual;
            $codigoCliente = $userActual->empresa->codigo.'.'.str_pad(count($totalCliente) + 1, 4, '0', STR_PAD_LEFT).'.'.$year_last_two_digits;
            $codigoUser = $userActual->empresa->codigo.'.'.str_pad(count($totalUser) + 1, 5, '0', STR_PAD_LEFT).'.'.$year_last_two_digits;

            $valorPago = $request->input('valor_pago');
            $dividaAnterior = $request->input('divida');
            $valorAPagar = $contrato->valor_contrato;
            $estado = 'Pendente';
            $saldo =  $valorPago - $valorAPagar;
            $divida = $valorAPagar - $valorPago;

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
            $cliente->nacionalidade_id = $request->input('nacionalidade');
            $cliente->local_emissao_id = $request->input('local_emissao');
            $cliente->data_emissao = $request->input('data_emissao');

            if ($cliente->save()) {

                $furoClienteContrato = new FuroClienteContrato();

                if($valorPago == $valorAPagar || $valorPago > $valorAPagar ){
                    $furoClienteContrato->estado_pagamento = 'Pago';
                    $estado = 'Pago';
                }elseif($valorPago == 0){
                    $furoClienteContrato->estado_pagamento = 'Pendente';
                    $estado = 'Pendente';
                }elseif($valorPago > 0 && $valorPago != $valorAPagar){
                    $furoClienteContrato->estado_pagamento = 'Parcial';
                    $estado = 'Parcial';
                }

                $dividaContracto = 0;

                if($saldo > 0){
                    $furoClienteContrato->saldo = $saldo;
                }
                if($divida > 0){
                    $dividaContracto = $divida;  
                }

                $furoClienteContrato->divida = $dividaContracto+$dividaAnterior;
                $furoClienteContrato->valor_pago = $valorPago;
                $furoClienteContrato->valor_a_pagar = $valorAPagar;
                $furoClienteContrato->multa = $request->input('multa');
                $furoClienteContrato->ultima_leitura = $request->input('leitura');
                $furoClienteContrato->user_id = $userActual->id;
                $furoClienteContrato->data_multa = $request->input('prazo_pagamento');
                $furoClienteContrato->codigo = $codigoCliente;
                $furoClienteContrato->ultimo_pagamento = $data;
                $furoClienteContrato->bairro = $request->input('bairro');
                $furoClienteContrato->quarteirao = $request->input('quarteirao');
                $furoClienteContrato->casa = $request->input('casa');
                $furoClienteContrato->telefone_notificar = $request->input('telefone');
                $furoClienteContrato->contador = $request->input('numero_contador');
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
                            $pagamento->furo_id = $userActual->furo_id;
                            $pagamento->user_id = $userActual->id;
                            $pagamento->factura_id = $factura->id;
                            $pagamento->estado = $estado;
                            $pagamento->forma_pagamento_id = $request->input('forma_pagamento');
                            $pagamento->tipo_pagamento_id = 1;
                            $pagamento->descricao = $request->input('descricao');
                            $pagamento->tipo_banco = $request->input('banco_carteira');

                            if($pagamento->save()){

                                $recibo = new Recibo();
                                $recibo->cliente_id = $furoClienteContrato->id;
                                $recibo->empresa_id = $userActual->empresa_id;
                                $recibo->furo_id = $userActual->furo_id;
                                $recibo->numero_factura = $numeroFatura;
                                $recibo->status = $estado;
                                $recibo->tipo_pagamento_id = 1;
                                $recibo->contrato_id = $furoClienteContrato->id;
                                $recibo->tipo_pagamento_id = 1;
                                $recibo->valor = $valorAPagar;
                                $recibo->pagamento_id = $pagamento->id;
                                $recibo->numero_recibo = $numeroRecibo;

                                $userCliente =new User();
                                $userCliente->nome = $request->input('cliente');
                                $userCliente->telefone = $request->input('telefone');
                                $userCliente->codigo = $codigoCliente;
                                $userCliente->tipo = 'Cliente';
                                $userCliente->password = bcrypt($request->input('telefone'));
                                $userCliente->distrito_id = $request->input('distrito');
                                $userCliente->empresa_id = $userActual->empresa_id;
                                $userCliente->furo_id = $request->input('furo');

                                if($recibo->save() && $userCliente->save()){

                                    DB::commit();
                                    return response()->json(['status' => 1, 'message' => 'Cliente Registado Com Sucesso']);

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

    public function update(Request $request)
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
            $year_last_two_digits = substr($anoActual, -2);
            //Factura e Recibo
            $totalFatura = Fatura::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();
            $totalRecibo = Recibo::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();
            $totalCliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->get();

            $numeroFatura = str_pad(count($totalFatura) + 1, 7, '0', STR_PAD_LEFT).'-'.$anoActual;
            $numeroRecibo = str_pad(count($totalRecibo) + 1, 7, '0', STR_PAD_LEFT).'-'.$anoActual;
            $codigoCliente = $userActual->empresa->codigo.'.'.str_pad(count($totalCliente) + 1, 3, '0', STR_PAD_LEFT).'.'.$year_last_two_digits;

            $valorPago = $request->input('valor_pago');
            $valorAPagar = $contrato->valor_contrato;
            $estado = 'Pendente';
            $saldo =  $valorPago - $valorAPagar;
            $divida = $valorAPagar - $valorPago;

            // Cria Empresa e colocar Saldo de SMSCredito
            $cliente = Cliente::where('id',$request->input('clienteID'))->first();
            $cliente->nome = $request->input('cliente');
            $cliente->empresa_id = $userActual->empresa_id;
            $cliente->tipo_documento = $request->input('tipo_documento');
            $cliente->numero_documento = $request->input('numero_documento');
            $cliente->quarteirao = $request->input('quarteirao');
            $cliente->casa = $request->input('casa');
            $cliente->distrito_id = $request->input('distrito');
            $cliente->furo_id = $request->input('furo');

            if ($cliente->save()) {

                $furoClienteContrato = FuroClienteContrato::where('codigo',$request->input('codigo'))->first();

                if($furoClienteContrato->valor_pago!=$valorPago){

                    if($valorPago == $valorAPagar || $valorPago > $valorAPagar ){
                         $furoClienteContrato->estado_pagamento = 'Pago';
                         $estado = 'Pago';
                    }elseif($valorPago == 0){
                        $furoClienteContrato->estado_pagamento = 'Pendente';
                        $estado = 'Pendente';
                    }elseif($valorPago > 0 && $valorPago != $valorAPagar){
                        $furoClienteContrato->estado_pagamento = 'Parcial';
                        $estado = 'Parcial';
                    }

                    if($saldo > 0){
                        $furoClienteContrato->saldo = $saldo;
                    }
                    if($divida > 0){
                        $furoClienteContrato->divida = $divida;
                    }

                }

                $furoClienteContrato->valor_pago = $valorPago;
                $furoClienteContrato->valor_a_pagar = $valorAPagar;
                $furoClienteContrato->multa = $request->input('multa');
                $furoClienteContrato->ultima_leitura = $request->input('leitura');
                $furoClienteContrato->user_id = $userActual->id;
                $furoClienteContrato->data_multa = $request->input('prazo_pagamento');
                //$furoClienteContrato->codigo = $codigoCliente;
                $furoClienteContrato->ultimo_pagamento = $data;
                $furoClienteContrato->bairro = $request->input('bairro');
                $furoClienteContrato->quarteirao = $request->input('quarteirao');
                $furoClienteContrato->casa = $request->input('casa');
                $furoClienteContrato->telefone_notificar = $request->input('telefone');
                $furoClienteContrato->contador = $request->input('numero_contador');
                $furoClienteContrato->contrato_id  = $request->input('contrato');
                $furoClienteContrato->distrito_id = $request->input('distrito');
                $furoClienteContrato->provincia_id = $request->input('provincia');
                $furoClienteContrato->ano_inicio_id = $ano->id;
                $furoClienteContrato->mes_inicio_id = $mes->id;
                $furoClienteContrato->furo_id = $request->input('furo');
                $furoClienteContrato->empresa_id = $userActual->empresa_id;
                $furoClienteContrato->cliente_id = $cliente->id;

                if($furoClienteContrato->save()){

                    $factura = Fatura::where('id',$request->input('factura'))->first();
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

                            $pagamento = Pagamento::where('id',$request->input('pagamentoID'))->first();
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
