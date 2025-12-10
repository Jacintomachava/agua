<?php

namespace App\Jobs;

use App\Models\MensagemPeriodica;
use App\Models\FuroClienteContrato;
use App\Models\Mensagem;
use App\Services\SMSService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class EnviarMensagemPeriodicaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $hoje = now()->day; // dia atual (1 a 31)
        $data = Carbon::now();
        // Buscar mensagens programadas para hoje
        $mensagens = MensagemPeriodica::where('dia_do_mes', $hoje)->get();

        foreach ($mensagens as $msg) {

            // Buscar todos os clientes da empresa
            $clientes = FuroClienteContrato::where('empresa_id', $msg->empresa_id)->get();

            foreach ($clientes as $cliente) {
                
                // Criar uma mensagem na tabela mensagem (como já faz no seu sistema)
                $smsDescricao = $msg->descricao;

                $sms = new Mensagem();
                $sms->descricao = $smsDescricao;
                $sms->telefone = $cliente->telefone_notificar;
                $sms->nome = $cliente->cliente->nome;
                $sms->qtd = SMSService::quantidadeSMS($smsDescricao);
                $sms->credito = SMSService::quantidadeSMS($smsDescricao)*1.8;
                $sms->custo_real = SMSService::quantidadeSMS($smsDescricao)*1.2;
                $sms->empresa_id = $msg->empresa_id;
                $sms->furo_id = $cliente->furo_id;
                $sms->data_envio = $data;
                $sms->save();

            }
        }

        \Log::info("Mensagens periódicas enviadas com sucesso.");
    
    }
}
