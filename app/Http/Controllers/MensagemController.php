<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompraCredito;
use App\Models\Mensagem;
use App\Models\Furo;
use App\Models\User;
use App\Models\FuroClienteContrato;
use App\Models\SaldoSMS;
use App\Services\SMSService;
use App\Models\MensagemSessao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MensagemController extends Controller
{
    //
    public function index()
    {
        $mensagens = Mensagem::orderBy('updated_at', 'desc')->get();
        $contatos = MensagemSessao::orderBy('updated_at', 'desc')->get();
        $pacotes = CompraCredito::orderBy('updated_at', 'desc')->get();

        // 🟢 Gráfico 1: Consumo de créditos por mês e canal (WhatsApp e SMS)
        $consumoPorCanal = DB::table('mensagem')
                    ->selectRaw("DATE_FORMAT(created_at, '%M') as mes, canal, SUM(credito) as total_credito")
                    ->groupBy('mes', 'canal')
                    ->orderBy('mes')
                    ->get();

            // Obter os meses únicos
        $meses = $consumoPorCanal->pluck('mes')->unique()->values();

        // 🟢 Gráfico 2: Consumo de crédito e lucro por mês
        $consumoLucroPorMes = Mensagem::selectRaw("DATE_FORMAT(created_at, '%M') as mes, SUM(credito) as total_credito, SUM(credito - custo_real) as lucro")
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Retorna a view com os dados carregados
        return view('mensagem.index', [
            'mensagens' => $mensagens,
            'contatos' => $contatos,
            'pacotes' => $pacotes,
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

        // Retorna a view com os dados carregados
        return view('mensagem.show', [
            'mensagens' => $mensagens,
            'contatos' => $contatos,
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
