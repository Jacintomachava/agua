<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Leitura;
use App\Models\FuroClienteContrato;
use App\Models\Ano;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LeituraJob implements ShouldQueue
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
        $anoActual =  Carbon::now()->year;
        $mesAtual = Carbon::now()->month;
        $ano = Ano::where('ano',$anoActual)->first();

        $clientes = FuroClienteContrato::where('ligacao_activa',true)->get();


        foreach ($clientes as $cliente) {

            $verificarMes = Leitura::where('mes_id',$mesAtual-1)->where('ano_id',$ano->id)->where('furo_cliente_contrato_id',$cliente->id)->first();

            if($verificarMes==null){

                $leitura = new Leitura();
                $leitura->data_leitura = Carbon::now();
                $leitura->ultimo_pagamento = Carbon::now();
                $leitura->empresa_id = $cliente->empresa_id;
                $leitura->furo_cliente_contrato_id = $cliente->id;
                $leitura->furo_id = $cliente->furo_id;
                $leitura->ano_id  = $ano->id;
                $leitura->mes_id  = $mesAtual-1;
                $leitura->save();

                \Log::info("Leitura Preparada".$leitura->id);
            }      
        }
    }
}
