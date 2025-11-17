<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\SMSCredito;
use App\Models\User;
use App\Models\Ano;
use App\Models\Subscricao;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    
    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            // Cria Empresa e colocar Saldo de SMSCredito
            $empresa = new Empresa();
            $empresa->nome = $request->input('nome_empresa');
            $empresa->nuit = $request->input('nuit');
            $empresa->telefone = $request->input('telefone_user');
            $empresa->distrito_id = $request->input('distrito');
            $empresa->endereco = $request->input('bairro');

            $anoActual = Carbon::now()->year;
            $ano = Ano::where('ano',$anoActual)->first();

            if ($empresa->save()) {

                $smsCredito = new SMSCredito();
                $smsCredito->empresa_id = $empresa->id;

                $user = new User();
                $user->nome = $request->input('nome_user');
                $user->telefone = $request->input('telefone_user');
                $user->distrito_id = $request->input('distrito');
                $user->password = bcrypt($request->input('telefone_user'));
                $user->empresa_id = $empresa->id;

                $subscricao = new Subscricao();
                $subscricao->valor = 0;
                $subscricao->desconto = 0;
                $subscricao->plano = 0;
                $subscricao->ano_id = $ano->id;
                $subscricao->empresa_id = $empresa->id;

                if($smsCredito->save() && $user->save() && $subscricao->save()){

                    DB::commit();
                    return response()->json(['status' => 1, 'message' => 'Empresa Criada Com Sucesso']);
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
