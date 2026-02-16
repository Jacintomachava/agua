<?php

namespace App\Jobs;

use App\Models\Mensagem;
use App\Models\MensagemSessao;
use App\Models\SaldoSMS;
use App\Services\SMSService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class EnviarSMS implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->enviarSMS();
    }

    private function enviarSMS()
    {
        $hoje = now()->toDateString(); // ou $data = '2025-04-14';
        $horaAtual = now()->format('H:i'); // Exemplo: 13:45

        $mensagens = Mensagem::where('tipo', 'Pendente')
                    ->where('data_envio','<=', $hoje)
                    ->where('canal', 'SMS')
                    ->get();

        //\Log::info('📩 Total de Mensagens por Enviar: '.$mensagens->count());

        if ($horaAtual >= '08:00' && $horaAtual <= '20:00' && $mensagens->count() > 0) {
            foreach ($mensagens as $mensagem) {
                $saldo = SaldoSMS::where('empresa_id',$mensagem->empresa_id)->first();
                $actualizarMensagem = Mensagem::where('id', $mensagem->id)->first();

                if (!$saldo || $saldo->saldo < 0) {

                    if($saldo->notificado == 0){
                    
                        $this->notificarSaldoInsuficiente();
                    }

                    \Log::warning('❌ Interação bloqueada. Saldo insuficiente.');
                    $saldo->notificado = 1;
                    $saldo->save();

                    \Log::info('❌ Interação bloqueada. Saldo insuficiente.');

                    return true;
                }

                // Enviar SMS
                $resultado = SMSService::sendSMS($mensagem->telefone, $mensagem->descricao, 'LHAYSSO');

                if($resultado==200){

                    // Actaulizar Saldo
                    $saldo->saldo = $saldo->saldo - $mensagem->qtd * 1.85;
                    // Actualizar SMS
                    $actualizarMensagem->tipo = 'Enviada';
                    $actualizarMensagem->credito = $mensagem->qtd * 1.85;
                    $actualizarMensagem->custo_real = $mensagem->qtd * 1.35;
                    $actualizarMensagem->lucro = $mensagem->qtd * 1.85 - $mensagem->qtd * 1.35;
                    $actualizarMensagem->saldo_sms = $saldo->saldo;
                    $actualizarMensagem->save();
                    //Diminuir
                    $saldo->save();

                    // Atualizando a sessão do usuário
                    MensagemSessao::updateOrCreate(
                        ['telefone' => $mensagem->telefone], // Critério de busca
                        [
                            'ultima_conversa' => $mensagem->descricao,
                            'nome' => $mensagem->nome,
                            'conversas' => DB::raw('conversas + 1'), // Incrementar conversas
                            'created_at' => now(), // Atualizar data de criação quando necessário
                        ]
                    );

                    //\Log::info('📩 Hora normal de Atendimento...');

                }elseif($resultado==402){

                    \Log::warning('❌ Interação bloqueada por MOZSMS, Saldo insuficiente. ');

                }else{

                    // Actualizar SMS
                    $actualizarMensagem->tipo = 'Inválido';
                    $actualizarMensagem->save();
                }

            }
        } else {
           // \Log::info('📩 Fora da hora normal de Atendimento ou sem nada para enviar');
        }
    }

    // 🔹 Método para notificar sobre saldo insuficiente
    private function notificarSaldoInsuficiente()
    {
        $telefone = '874870386';
        $mensagem = 'O furo está sem crédito para enviar SMS e interagir via WhatsApp. Por favor, recarregue a sua conta.';
        SMSService::sendSMS($telefone, $mensagem, 'LHAYSSO');
        \Log::info('📩 Cliente notificado com sucesso.');
    }
}
