<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensalidade;
use App\Models\CoWork;
use App\Models\Mensagem;
use App\Models\Levantamento;
use App\Models\User;
use App\Models\Credencial;
use App\Models\Mes;
use App\Models\DivisaoLucroUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CoWorkController extends Controller
{
    //
    public function index()
    {
        $userActual = Auth::user();
        $mesAtual = Carbon::now()->month;
        $anoAtual = Carbon::now()->year;

        if($userActual->tipo == 'CoWork'){

            $totalSubscricao = DivisaoLucroUser::where('user_id',$userActual->id)->where('mes_pagamento_id',$mesAtual)->sum('valor');
            $saldo = User::where('id',$userActual->id)->first();
            $nrEmpresa = CoWork::where('user_id',$userActual->id)->count();
            // Pega todos os meses
            $meses = Mes::orderBy('id')->pluck('nome')->toArray();
            // Inicializa o array de lucros
            $dadosGrafico = [];
            // Pega os valores do usuário por mês
            $dadosGrafico2 = [];

            foreach(Mes::orderBy('id')->get() as $mes){
                $lucroMes = Mensagem::join('co_works', 'mensagem.empresa_id', '=', 'co_works.empresa_id')
                    ->where('co_works.user_id', $userActual->id)
                    ->whereMonth('mensagem.created_at', $mes->id)
                    ->sum(\DB::raw('mensagem.lucro * co_works.percentagem / 100'));

                $dadosGrafico[] = [
                    'name' => $mes->nome,
                    'value' => round($lucroMes, 2)
                ];
            }

            foreach(Mes::orderBy('id')->get() as $mes) {
                $valor = DivisaoLucroUser::where('user_id', $userActual->id)
                            ->where('mes_pagamento_id', $mes->id)
                            ->sum('valor'); // soma caso haja mais de um registro

                $dadosGrafico2[] = [
                    'name' => $mes->nome,
                    'value' => round($valor, 2)
                ];
            }
            
            $coworks = Cowork::all()->map(function($c) {
                $totalLucro = Mensagem::where('empresa_id', $c->empresa_conquistada_id)
                    ->where('tipo', 'enviado')
                    ->sum('lucro');

                $c->total_receber = $totalLucro * ($c->percentagem / 100);
                return $c;
            });

            // Somar todos os valores
            $totalReceberGeral = $coworks->sum('total_receber');

            // Retorna a view com os dados carregados
            return view('cowork.index', [
                'totalSubscricao' => $totalSubscricao,
                'saldo' => $saldo,
                'nrEmpresa' => $nrEmpresa,
                'totalReceberGeral' => $totalReceberGeral,
                'meses' => $meses,
                'dadosGrafico' => $dadosGrafico,
                'dadosGrafico2' => $dadosGrafico2,
            ]);

        }

        if($userActual->tipo == 'Dono'){

            $userActual = Auth::user();
            
            $divisoes = DivisaoLucroUser::where('user_id',$userActual->id)->get();
            $saldo = User::where('id',$userActual->id)->first();

             // Retorna a view com os dados carregados
            return view('dono.divisao', [ 
                'saldo' => $saldo->saldo,
                'divisoes' => $divisoes,
            ]);

        }
        
    }

    public function minhasEmpresas()
    {
        $userActual = Auth::user();

        $empresas = CoWork::where('user_id',$userActual->id)->get();

         return view('cowork.minhasEmpresas', [
            'empresas' => $empresas,
        ]);

    }

    public function minhasMensalidades()
    {
        $userActual = Auth::user();

        $empresas = DivisaoLucroUser::where('user_id',$userActual->id)->get();

         return view('cowork.minhasMensalidades', [
            'empresas' => $empresas,
        ]);

    }

    public function levantamento()
    {
        $userActual = Auth::user();

        $levantamentos = Levantamento::where('user_id',$userActual->id)->get();

         return view('cowork.levantamentos', [
            'levantamentos' => $levantamentos,
        ]);

    }

    public function fazerLevantamento(Request $request)
    {

        try {

            DB::beginTransaction();

            $anoActual = Carbon::now()->year;
            $userActual = Auth::user();
            $telefone = $request->input('telefone');
            $valor = $request->input('valor');

            $totalLevantamento = Levantamento::all();
            $credenciaisMpesa = Credencial::where('empresa_id',1)->first();
            $novoSaldo = User::where('id',$userActual->id)->first();
            $numeroFatura = str_pad(count($totalLevantamento) + 1, 7, '0', STR_PAD_LEFT).''.$anoActual;
            $codigo1 = Str::random(8);
            $referencia = $numeroFatura;

            // Expressão regular: começa com 84 ou 85 e tem 9 dígitos no total
            if (!preg_match('/^8[45]\d{7}$/', $telefone)) {
                return response()->json(['status' => 0, 'message' => 'Número inválido. Deve começar com 84 ou 85']);
            }

            if($novoSaldo->saldo - $valor < 0){
                return response()->json(['status' => 0, 'message' => 'Saldo insuficiente para levantamento']);
            }

            $levantamento = new Levantamento();
            $levantamento->saldo_actual = $userActual->saldo;
            $levantamento->valor_levantado = $valor;
            $levantamento->telefone = $telefone;
            $levantamento->codigo = $numeroFatura;
            $levantamento->user_id = $userActual->id;

            $novoSaldo->saldo = $novoSaldo->saldo - $valor;
            $novoSaldo->save();

            if($levantamento->save()){

                $mpesa = new \Karson\MpesaPhpSdk\Mpesa();

                $mpesa->setApiKey($credenciaisMpesa->api_key);
                $mpesa->setPublicKey($credenciaisMpesa->public_key);
                $mpesa->setServiceProviderCode($credenciaisMpesa->service_provaider_code);
                $mpesa->setEnv($credenciaisMpesa->env); // 'live' production environment

                $invoice_id = str_replace('-', '', $referencia); // Eg: Invoice number
                $phone_number = '258' . preg_replace('/^(\+?258)?/', '', $telefone); // Prefixed with country code (258)
                $amount = $valor; // Payment amount
                $reference_id = $invoice_id.''.$codigo1; // Should be unique for each transaction

                //Fazer o Levantamento - Tirar valor do fundo da empresa para o cliente
                $result = $mpesa->b2c($invoice_id, $phone_number, $amount, $reference_id);

                if ($result->response->output_ResponseCode == 'INS-0') {

                    DB::commit();

                    return response()->json(['status' => 1, 'message' => 'Levantamento Efectuado com Sucesso']);

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


        } catch (\Exception $e) {

            DB::rollBack();
            //$errorMessage = DatabaseErrorHandler::handle($e);
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }

    }

    public function creditoMensagem()
    {
        $userActual = Auth::user();

        $registos = Mensagem::query()
            ->join('co_works', 'mensagem.empresa_id', '=', 'co_works.empresa_id')
            ->where('co_works.user_id', $userActual->id)
            ->whereMonth('mensagem.created_at', now()->month)
            ->whereYear('mensagem.created_at', now()->year)
            ->select(
                'mensagem.*',
                'co_works.percentagem',
                \DB::raw('(mensagem.lucro * co_works.percentagem / 100) as lucro_cowork')
            )
            ->get();

         return view('cowork.creditoMensagem', [
            'registos' => $registos,
        ]);

    }
}
