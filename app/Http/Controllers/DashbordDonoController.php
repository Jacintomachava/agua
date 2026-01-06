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
use App\Models\CompraCredito;
use App\Models\DivisaoLucro;
use App\Models\SaldoSMS;
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

class DashbordDonoController extends Controller
{
    //
    public function index()
    {
        $userActual = Auth::user();

        //Dados Estatistico
        $totalempresas = Empresa::where('id','<>',1)->count();
        $totalclientes = FuroClienteContrato::count();
        $totalCredito = Mensagem::where('empresa_id','<>',1)->sum('credito');
        $saldoDispinivel = SaldoSMS::where('empresa_id',1)->first();
        $totalCowork = User::where('tipo','CoWork')->count();

        //Outros Dados
        $empresas = Empresa::where('id','<>',1)->latest()->take(6)->get();
        $coworks = User::where('empresa_id','<>',1)->get();
        $mensagens = Mensagem::where('empresa_id', '<>', 1)->latest('updated_at') ->take(6)->get();

        //Comissoes
        $comissaoCowork = DivisaoLucro::sum('valor_co_work');
        $comissaoManutencao = DivisaoLucro::sum('valor_manutencao');
        $comissaoDono = DivisaoLucro::sum('valor_sistema');

        //Compra de Credito
        $compraCredito = CompraCredito::where('empresa_id','<>',1)->sum('valor');

        return view('dono.home',  [
             'empresas' => $empresas,
             'coworks' => $coworks,
             'mensagens' => $mensagens,

             //Dados Estatitico
             'totalempresas' => $totalempresas,
             'totalclientes' => $totalclientes,
             'totalCredito' => $totalempresas,
             'saldoDispinivel' => $saldoDispinivel->saldo,
             'totalCowork' => $totalCowork,

             //Comissoes
             'comissaoCowork' => $comissaoCowork,
             'comissaoManutencao' =>$comissaoManutencao,
             'comissaoDono' => $comissaoDono,

             //Compra de Credito
             'compraCredito' => $compraCredito,
        ]);
    }

    public function listarPagamentos()
    {
    
        $userActual = Auth::user();

        $coworks = User::where('tipo','CoWork')->get();

        return view('dono.cowork.index',  [
             'coworks' => $coworks,
        ]);
    }

    public function listarCoworks()
    {
        $userActual = Auth::user();

        $coworks = User::where('tipo','CoWork')->get();

        return view('dono.cowork.index',  [
             'coworks' => $coworks,
        ]);
    }

    public function listarEmpresas()
    {
        $userActual = Auth::user();

        $empresas = Empresa::where('id','<>',1)->get();

        return view('dono.empresa.index',  [
             'empresas' => $empresas,
        ]);
    }
}
