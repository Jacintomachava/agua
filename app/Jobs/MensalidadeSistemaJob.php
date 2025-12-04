<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Ano;
use App\Models\Empresa;
use App\Models\Fatura;
use App\Models\Leitura;
use App\Models\Mensalidade;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MensalidadeSistemaJob implements ShouldQueue
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
        $mesAnterior = Carbon::now()->subMonth()->month;
        $ano = Ano::where('ano',$anoActual)->first();

        $empresas = Empresa::where('estado',1)->where('furo',1)->get();

        foreach ($empresas as $empresa) {

            $nrClientes = Leitura::where('estado_leitura',1)->where('ano_id',$ano->id)->where('mes_id',$mesAnterior)->where('empresa_id',$empresa->id)->count();
            $mensalidadeNulas = Mensalidade::where('ano_id',$ano->id)->where('mes_id',$mesAnterior)->where('empresa_id',$empresa->id)->first();

            $totalFatura = Fatura::where('empresa_id', 1)->get();
            $numeroFatura = str_pad(count($totalFatura) + 1, 7, '0', STR_PAD_LEFT).'-'.$anoActual;

            if($mensalidadeNulas==null){

                $mensalidade = new Mensalidade();
                $mensalidade->pagou = false;
                $mensalidade->clientes = $nrClientes;
                $mensalidade->valor_por_cliente = $empresa->valor_por_cliente;
                $mensalidade->multa = 0;
                $mensalidade->codigo = $numeroFatura;
                $mensalidade->mes_id = $mesAnterior;
                $mensalidade->ano_id = $ano->id;
                $mensalidade->empresa_id = $empresa->id;
                $mensalidade->forma_pagamento_id = 2;
                $mensalidade->banco_carteira_id = 2;
                $mensalidade->prazo_pagamento = Carbon::now()->addDays(25)->format('Y-m-d');
                $mensalidade->save();

                //Criacao de Factura
                $factura = new Fatura();
                $factura->cliente_id = $empresa->id;
                $factura->empresa_id = 1;
                $factura->numero_factura = $numeroFatura;
                $factura->data_emissao = Carbon::now();
                $factura->status = 'Pendente';
                $factura->tipo_pagamento_id = 2;
                $factura->subscricao_id = $mensalidade->id;
                $factura->valor = $nrClientes*$empresa->valor_por_cliente;
                //$factura->furo_id = $leitura->furo_id;
                $factura->save();

                \Log::info("Factura Gerado para Empresa ".$numeroFatura);
            }

        }

    }
}
