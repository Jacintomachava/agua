<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensalidade;
use App\Models\Credencial;
use App\Models\Recibo;
use App\Models\Fatura;
use App\Models\Empresa;
use App\Models\Pagamento;
use App\Models\User;
use App\Models\DivisaoLucroUser;
use App\Models\DivisaoLucro;
use App\Models\CoWork;
use App\Models\Dono;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PagamentoSubscricaoController extends Controller
{
    
    public function show($codigo)
    {
        $mensalidade = Mensalidade::where('codigo',$codigo)->first();
        // Retorna a view com os dados carregados
        return view('subscricao.show', [
            'mensalidade' => $mensalidade,
        ]);
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            $userActual = Auth::user();
            $mensalidade = Mensalidade::where('codigo',$request->input('codigo'))->first();
            $fatura = Fatura::where('subscricao_id',$mensalidade->id)->first();
            $credenciaisMpesa = Credencial::where('empresa_id',1)->first();
            $valorPago = $request->input('valor');
            $telefone = $request->input('telefone');

            $data = Carbon::now();
            $anoActual = Carbon::now()->year;
            $mesAtual = Carbon::now()->month;
            $totalRecibo = Recibo::where('empresa_id',$userActual->empresa_id)->get();
            $numeroRecibo = str_pad(count($totalRecibo) + 1, 7, '0', STR_PAD_LEFT).'-'.$anoActual;
            $codigo1 = Str::random(8);
            $referencia = substr($numeroRecibo, 0, -5);

            // Expressão regular: começa com 84 ou 85 e tem 9 dígitos no total
            if (!preg_match('/^8[45]\d{7}$/', $telefone)) {

                return response()->json(['status' => 0, 'message' => 'Número inválido. Deve começar com 84 ou 85']);
            }

            $pagamento = new Pagamento();
            $pagamento->valor = $valorPago;
            //$pagamento->furo_id = $request->input('furo');
            $pagamento->empresa_id = 1;
            //$pagamento->leitura_id = $leitura->id;
            $pagamento->factura_id = $fatura->id;
            $pagamento->estado = 'Pago';
            $pagamento->forma_pagamento_id = 2;
            $pagamento->tipo_pagamento_id = 3;
            $pagamento->descricao = 'Pagamento de Mensalidade de mes '.$mensalidade->mes->nome.'-'.$mensalidade->ano->ano;
            $pagamento->tipo_banco = 2;

            if($pagamento->save()){

                $recibo = new Recibo();
                $recibo->cliente_id = $userActual->empresa_id;
                $recibo->empresa_id = 1;
                $recibo->numero_factura = $mensalidade->codigo;
                $recibo->status = 'Pago';
                $recibo->tipo_pagamento_id = 3;
                $recibo->factura_id = $fatura->id;
                $recibo->valor = $valorPago;
                $recibo->pagamento_id = $pagamento->id;

                //Mudar Estado Pagamento
                $mensalidade->pagou = true;
                $mensalidade->user_id = $userActual->id;
                $mensalidade->data_pagamento = Carbon::now();

                if($recibo->save() && $mensalidade->save()){

                    $coWork = CoWork::where('empresa_id',$userActual->empresa_id)->first();
                    $percentagemCowork = 0;

                    $divisaoLucro = new DivisaoLucro();

                    if($coWork!=null){
                        $divisaoLucro->co_work_id = $coWork->id;
                        $divisaoLucro->percentagem_co_work = $coWork->percentagem;
                        $divisaoLucro->valor_co_work = $valorPago * ($coWork->percentagem /100);

                        $percentagemCowork = $coWork->percentagem;

                        $divisaoLucroUser = new DivisaoLucroUser();
                        $divisaoLucroUser->valor = $valorPago * ($coWork->percentagem /100);
                        $divisaoLucroUser->user_id = $coWork->user_id;
                        $divisaoLucroUser->empresa_id = $userActual->empresa_id;
                        $divisaoLucroUser->pagamento_id = $pagamento->id;
                        $divisaoLucroUser->mes_pagamento_id = $mesAtual;
                        $divisaoLucroUser->mes_factura_id = $mensalidade->mes_id;
                        $divisaoLucroUser->valor_pago = $valorPago;
                        $divisaoLucroUser->percentagem = $coWork->percentagem;
                        $divisaoLucroUser->save();

                        $user = User::where('id',$coWork->user_id)->first();
                        $user->saldo = $user->saldo + $valorPago * ($coWork->percentagem /100);
                        $user->save();
                    }

                    $percentagemSistema = 100 - $percentagemCowork - 20;

                    $divisaoLucro->percentagem_sistema = $percentagemSistema;
                    $divisaoLucro->percentagem_manutencao = 20;
                    $divisaoLucro->valor_pago = $valorPago;
                    $divisaoLucro->valor_sistema = $valorPago*($percentagemSistema/100);
                    $divisaoLucro->valor_manutencao = $valorPago*(20/100);
                    $divisaoLucro->empresa_id = $userActual->empresa_id;
                    $divisaoLucro->pagamento_id = $pagamento->id;

                    if($divisaoLucro->save()){

                        $donos = Dono::all();

                        foreach ($donos as $dono) {

                            $divisaoLucroUser = new DivisaoLucroUser();
                            $divisaoLucroUser->valor = $valorPago * ($dono->percentagem /100);
                            $divisaoLucroUser->user_id = $dono->user_id;
                            $divisaoLucroUser->empresa_id = $userActual->empresa_id;
                            $divisaoLucroUser->pagamento_id = $pagamento->id;
                            $divisaoLucroUser->mes_pagamento_id = $mesAtual;
                            $divisaoLucroUser->mes_factura_id = $mensalidade->mes_id;
                            $divisaoLucroUser->valor_pago = $valorPago;
                            $divisaoLucroUser->percentagem = $dono->percentagem;
                            $divisaoLucroUser->save();

                            $user = User::where('id',$dono->user_id)->first();
                            $user->saldo = $user->saldo + $valorPago * ($dono->percentagem /100);
                            $user->save();

                        }

                        $mpesa = new \Karson\MpesaPhpSdk\Mpesa();

                        $mpesa->setApiKey($credenciaisMpesa->api_key);
                        $mpesa->setPublicKey($credenciaisMpesa->public_key);
                        $mpesa->setServiceProviderCode($credenciaisMpesa->service_provaider_code);
                        $mpesa->setEnv($credenciaisMpesa->env); // 'live' production environment

                        $invoice_id = str_replace('-', '', $referencia); // Eg: Invoice number
                        $phone_number = '258' . preg_replace('/^(\+?258)?/', '', $telefone); // Prefixed with country code (258)
                        $amount = $valorPago; // Payment amount
                        $reference_id = $invoice_id.''.$codigo1; // Should be unique for each transaction

                        $result = $mpesa->c2b($invoice_id, $phone_number, $amount, $reference_id);

                        if ($result->response->output_ResponseCode == 'INS-0') {

                            DB::commit();
                            return response()->json(['status' => 1, 'message' => 'Pagamento de Mensalidade feita com Sucesso!']);

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
