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

class ClienteFuroController extends Controller
{
    //
    public function index()
    {
        $userActual = Auth::user();

        $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('codigo', $userActual->codigo)->first();
        $leituras = Leitura::where('furo_cliente_contrato_id', $cliente->id)->where('estado_leitura',1)->get();
        $recibos = Recibo::where('cliente_id',$cliente->id)->get();
        $mensagens = Mensagem::where('telefone',$cliente->telefone_notificar)->get();

        return view('clientes.show',  [
             'cliente' => $cliente,
             'leituras' => $leituras,
             'recibos' => $recibos,
             'mensagens' => $mensagens,
        ]);
    }
}
