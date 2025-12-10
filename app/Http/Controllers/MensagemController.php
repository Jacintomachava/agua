<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompraCredito;
use App\Models\Mensagem;
use App\Models\Furo;
use App\Models\User;
use App\Models\Fatura;
use App\Models\Pagamento;
use Carbon\Carbon;
use App\Models\Recibo;
use App\Models\Credencial;
use App\Models\Empresa;
use App\Models\FuroClienteContrato;
use App\Models\SaldoSMS;
use App\Models\MensagemPeriodica;
use App\Services\SMSService;
use App\Models\MensagemSessao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MensagemController extends Controller
{
    //
    public function index()
    {

        $userActual = Auth::user();
        $mensagens = Mensagem::where('empresa_id',$userActual->empresa_id)->orderBy('updated_at', 'desc')->get();
        $contatos = MensagemSessao::orderBy('updated_at', 'desc')->get();
        $pacotes = CompraCredito::where('empresa_id',$userActual->empresa_id)->orderBy('updated_at', 'desc')->get();
        $saldo = SaldoSMS::where('empresa_id',$userActual->empresa_id)->first();
        $creditoSMSPendente = Mensagem::where('empresa_id',$userActual->empresa_id)->sum('credito');
        $mensagensPeriodicas = MensagemPeriodica::where('empresa_id',$userActual->empresa_id)->get();


        // 🟢 Gráfico 1: Consumo de créditos por mês e canal (WhatsApp e SMS)
        $consumoPorCanal = DB::table('mensagem')
                    ->selectRaw("DATE_FORMAT(created_at, '%M') as mes, canal, SUM(credito) as total_credito")
                    ->where('empresa_id',$userActual->empresa_id)
                    ->where('tipo', 'Enviada')
                    ->groupBy('mes', 'canal')
                    ->orderBy('mes')
                    ->get();

            // Obter os meses únicos
        $meses = $consumoPorCanal->pluck('mes')->unique()->values();

        // 🟢 Gráfico 2: Consumo de crédito e lucro por mês
        $consumoLucroPorMes = Mensagem::selectRaw("DATE_FORMAT(created_at, '%M') as mes, SUM(credito) as total_credito, SUM(credito - custo_real) as lucro")
            ->where('empresa_id',$userActual->empresa_id)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Retorna a view com os dados carregados
        return view('mensagem.index', [
            'mensagens' => $mensagens,
            'mensagensPeriodicas' => $mensagensPeriodicas,
            'contatos' => $contatos,
            'pacotes' => $pacotes,
            'saldo' => $saldo,
            'creditoSMSPendente' => $creditoSMSPendente,
            //Graficos
            'consumoPorCanal' => $consumoPorCanal,
            'consumoLucroPorMes' => $consumoLucroPorMes,
            'meses' => $meses,
        ]);

    }

    public function show($contacto)
    {
        $mensagens = Mensagem::where('telefone',$contacto)->orderBy('updated_at', 'asc')->get();
        $contatos = MensagemSessao::orderBy('updated_at', 'desc')->get();
        $contato = MensagemSessao::where('telefone',$contacto)->first();

        // Retorna a view com os dados carregados
        return view('mensagem.show', [
            'mensagens' => $mensagens,
            'contatos' => $contatos,
            'contato' => $contato,
            'contacto' => $contacto,
        ]);
    }

    public function create()
    {
        $userActual = Auth::user();
        $credito = SaldoSMS::where('empresa_id', $userActual->empresa_id)->first();

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

        // Retorna a view com os dados carregados
        return view('mensagem.create', [
            'credito' => $credito,
            'furos' => $furos,
        ]);
    }

    public function storeCompraSMS(Request $request)
    {
         try {

                DB::beginTransaction();

                $telefone = $request->input('telefone');
                $valor = $request->input('valor');

                $userActual = Auth::user();
                $totalRecibo = Recibo::where('empresa_id',$userActual->empresa_id)->get();
                $descricao = " Compra de SMS de ".$valor." MT codigo: ";
                $empresaMozSoft = Empresa::where('id',1)->first();
                $credenciaisMpesa = Credencial::where('empresa_id',1)->first();

                $data = Carbon::now();
                $anoActual = Carbon::now()->year;
                $totalFatura = Fatura::where('empresa_id',$userActual->empresa_id)->get();
                $numeroFatura = str_pad(count($totalFatura) + 1, 7, '0', STR_PAD_LEFT).''.$anoActual;
                $codigo1 = Str::random(8);
                $referencia = $numeroFatura;

                $pacoteNome = null;

                if($valor < 100){
                    $pacoteNome = 'GRÁTIS';
                }elseif($valor > 100 && $valor < 1000){
                    $pacoteNome = 'STANDARD';
                }elseif($valor > 1000 && $valor < 5000){
                    $pacoteNome = 'PLUS';
                }elseif($valor > 5000 && $valor < 10001){
                    $pacoteNome = 'PROFESSIONAL';
                }

                // Expressão regular: começa com 84 ou 85 e tem 9 dígitos no total
                if (!preg_match('/^8[45]\d{7}$/', $telefone)) {

                    return response()->json(['status' => 0, 'message' => 'Número inválido. Deve começar com 84 ou 85']);

                }

                //Criacao de Factura
                $factura = new Fatura();
                $factura->cliente_id = $userActual->empresa_id;
                $factura->empresa_id = $empresaMozSoft->id;
                $factura->numero_factura = $numeroFatura;
                $factura->data_emissao = $data;
                $factura->status = 'Pendente';
                $factura->tipo_pagamento_id = 2;
                $factura->valor = $valor;
                $factura->furo_id = $userActual->furo_id;

                if($factura->save()){

                    $pagamento = new Pagamento();
                    $pagamento->valor = $valor;
                    $pagamento->furo_id = $userActual->furo_id;
                    $pagamento->empresa_id = $userActual->empresa_id;
                    $pagamento->factura_id = $factura->id;
                    $pagamento->estado = "Pago";
                    $pagamento->forma_pagamento_id = 2;
                    $pagamento->tipo_pagamento_id = 2;
                    $pagamento->descricao = $descricao;
                    $pagamento->tipo_banco = 2;

                    if($pagamento->save()){

                        $recibo = new Recibo();
                        $recibo->cliente_id = $userActual->empresa_id;
                        $recibo->empresa_id = $empresaMozSoft->id;;
                        $recibo->numero_factura = $numeroFatura;
                        $recibo->status = 'Pago';
                        $recibo->tipo_pagamento_id = 1;
                        $recibo->factura_id = $factura->id;
                        $recibo->valor = $valor;
                        $recibo->pagamento_id = $pagamento->id;

                        if($recibo->save()){

                            //Faturacao da Encomenda
                            $pacote = new CompraCredito();
                            $pacote->numero_credito = $valor;
                            $pacote->preco_por_credito = 1;
                            $pacote->valor = $valor;
                            $pacote->tipo_pacote = $pacoteNome;
                            $pacote->user_id = $userActual->id;
                            $pacote->empresa_id = $userActual->empresa_id;
                
                            if($pacote->save()){

                                $credito = SaldoSMS::where('empresa_id',$userActual->empresa_id)->first();
                                $credito->saldo = $credito->saldo + $valor;

                                if($credito->save()){

                                    $mpesa = new \Karson\MpesaPhpSdk\Mpesa();
                                    // Credenciais  -- $credenciaisMpesa
                                    /*$mpesa->setApiKey('eotig3de5fbkdw0w0j68y12uhn77wy6b');
                                    $mpesa->setPublicKey('MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAmptSWqV7cGUUJJhUBxsMLonux24u+FoTlrb+4Kgc6092JIszmI1QUoMohaDDXSVueXx6IXwYGsjjWY32HGXj1iQhkALXfObJ4DqXn5h6E8y5/xQYNAyd5bpN5Z8r892B6toGzZQVB7qtebH4apDjmvTi5FGZVjVYxalyyQkj4uQbbRQjgCkubSi45Xl4CGtLqZztsKssWz3mcKncgTnq3DHGYYEYiKq0xIj100LGbnvNz20Sgqmw/cH+Bua4GJsWYLEqf/h/yiMgiBbxFxsnwZl0im5vXDlwKPw+QnO2fscDhxZFAwV06bgG0oEoWm9FnjMsfvwm0rUNYFlZ+TOtCEhmhtFp+Tsx9jPCuOd5h2emGdSKD8A6jtwhNa7oQ8RtLEEqwAn44orENa1ibOkxMiiiFpmmJkwgZPOG/zMCjXIrrhDWTDUOZaPx/lEQoInJoE2i43VN/HTGCCw8dKQAwg0jsEXau5ixD0GUothqvuX3B9taoeoFAIvUPEq35YulprMM7ThdKodSHvhnwKG82dCsodRwY428kg2xM/UjiTENog4B6zzZfPhMxFlOSFX4MnrqkAS+8Jamhy1GgoHkEMrsT5+/ofjCx0HjKbT5NuA2V/lmzgJLl3jIERadLzuTYnKGWxVJcGLkWXlEPYLbiaKzbJb2sYxt+Kt5OxQqC1MCAwEAAQ==');
                                    $mpesa->setServiceProviderCode('171717');
                                    $mpesa->setEnv('test'); */

                                    $mpesa->setApiKey($credenciaisMpesa->api_key);
                                    $mpesa->setPublicKey($credenciaisMpesa->public_key);
                                    $mpesa->setServiceProviderCode($credenciaisMpesa->service_provaider_code);
                                    $mpesa->setEnv($credenciaisMpesa->env); // 'live' production environment

                                    $invoice_id = str_replace('-', '', $referencia); // Eg: Invoice number
                                    $phone_number = '258' . preg_replace('/^(\+?258)?/', '', $telefone); // Prefixed with country code (258)
                                    $amount = $valor; // Payment amount
                                    $reference_id = $invoice_id.''.$codigo1; // Should be unique for each transaction

                                    $result = $mpesa->c2b($invoice_id, $phone_number, $amount, $reference_id);

                                    if ($result->response->output_ResponseCode == 'INS-0') {

                                        DB::commit();
                                        return response()->json(['status' => 1, 'message' => 'Crédito Comprado com Sucesso!']);

                                    } else {

                                        if ($result->response->output_ResponseCode == 'INS-1') {
                                            return response()->json(['status' => 0, 'message' => 'Erro Interno ']);
                                        }
                                        if ($result->response->output_ResponseCode == 'INS-6') {
                                            return response()->json(['status' => 0, 'message' => 'A transacao Falhou ']);
                                        }
                                        if ($result->response->output_ResponseCode == 'INS-5') {
                                            return response()->json(['status' => 0, 'message' => 'Transacao Cancelada por Cliente ']);
                                        }
                                        if ($result->response->output_ResponseCode == 'INS-9') {
                                            return response()->json(['status' => 0, 'message' => 'Tempo de Espera Esgotado ']);
                                        }
                                        if ($result->response->output_ResponseCode == 'INS-15') {
                                            return response()->json(['status' => 0, 'message' => 'Valor Invalido ']);
                                        }
                                        if ($result->response->output_ResponseCode == 'INS-25') {
                                            return response()->json(['status' => 0, 'message' => 'Credencial de segurança inválida']);
                                        }
                                        if ($result->response->output_ResponseCode == 'INS-25') {
                                            return response()->json(['status' => 0, 'message' => 'Credencial de segurança inválida']);
                                        }
                                        if ($result->response->output_ResponseCode == 'INS-2006') {
                                            return response()->json(['status' => 0,  'message' => 'Saldo Insuficiente ']);
                                        }
                                        if ($result->response->output_ResponseCode == 'INS-2001') {
                                            return response()->json(['status' => 0,  'message' => 'Senha Errada... ']);
                                        }

                                        return response()->json(['status' => 0, 'message' => 'Ocorreu um erro: '.$result->response->output_ResponseDesc]);
                                    }

                                }
                
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

    public function storeSMS(Request $request)
    {

        try {

            DB::beginTransaction();

            $userActual = Auth::user();
            $smsDescricao = $request->input('mensagem');
            $furo = $request->input('furo');
            $data = $request->input('data_envio');

            if($request->destinatario=='clientes'){

                $clientes = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('furo_id',$furo)->get();

                if($clientes->isEmpty()){
                    return response()->json(['status' => 0, 'message' => 'Nenhum Cliente Registado nesse Furo']);
                }

                foreach ($clientes as $cliente) {

                    //Gerar SMS de Recibo
                    $sms = new Mensagem();
                    $sms->descricao = $smsDescricao;
                    $sms->telefone = $cliente->telefone_notificar;
                    $sms->nome = $cliente->cliente->nome;
                    $sms->qtd = SMSService::quantidadeSMS($smsDescricao);
                    $sms->credito = SMSService::quantidadeSMS($smsDescricao)*1.8;
                    $sms->custo_real = SMSService::quantidadeSMS($smsDescricao)*1.2;
                    $sms->empresa_id = $userActual->empresa_id;
                    $sms->furo_id = $cliente->furo_id;
                    $sms->data_envio = $data;
                    $sms->save();

                }

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Mensagem Enviada Para Clientes']);
            }

            if($request->destinatario=='utilizadores'){

                $users = User::where('empresa_id',$userActual->empresa_id)->where('furo_id',$furo)->get();

                if($users->isEmpty()){
                    return response()->json(['status' => 0, 'message' => 'Nenhum Utilizador Registado nesse Furo']);
                }

                foreach ($users as $user) {

                    //Gerar SMS de Recibo
                    $sms = new Mensagem();
                    $sms->descricao = $smsDescricao;
                    $sms->telefone = $user->telefone;
                    $sms->nome = $user->nome;
                    $sms->qtd = SMSService::quantidadeSMS($smsDescricao);
                    $sms->credito = SMSService::quantidadeSMS($smsDescricao)*1.8;
                    $sms->custo_real = SMSService::quantidadeSMS($smsDescricao)*1.2;
                    $sms->empresa_id = $user->empresa_id;
                    $sms->furo_id = $user->furo_id;
                    $sms->data_envio = $data;
                    $sms->save();

                }

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Mensagem Enviada Para utilizadores']);

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

    public function store(Request $request)
    {

        try {

            DB::beginTransaction();
   
               //Faturacao da Encomenda
               $pacote = new CompraCredito();
               $pacote->numero_credito = $request->input('numero_credito');
               $pacote->preco_por_credito = $request->input('preco_credito');
               $pacote->valor = $request->input('valor_total'); 
               $pacote->tipo_pacote = $request->input('pacote');
   
               if($pacote->save()){

                  $credito = SaldoSMS::where('codigo','saldo')->first();
                  $credito->saldo = $credito->saldo + $request->input('numero_credito');

                  if($credito->save()){

                    DB::commit();
   
                    return response()->json(['status' => 1, 'message' => 'Crédito Comprado com Sucesso!']);
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
