<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Furo;
use App\Models\MensagemPeriodica;
use App\Services\SMSService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TempleteSMSController extends Controller
{
    //
    public function create()
    {
        $userActual = Auth::user();

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

        
        return view('smsperiodica.create',  [
                'furos' => $furos
        ]);
    }

    public function toggleEstado($id)
    {
        $msg = MensagemPeriodica::findOrFail($id);

        // Inverte o estado
        $msg->estado = !$msg->estado;
        $msg->save();

        return back()->with('success', 'Estado atualizado com sucesso!');
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            $userActual = Auth::user();

            // Cria Empresa e colocar Saldo de SMSCredito
            $mensagemPeriodica = new MensagemPeriodica();
            $mensagemPeriodica->titulo = $request->input('titulo');
            $mensagemPeriodica->descricao = $request->input('mensagem');
            $mensagemPeriodica->dia_do_mes = $request->input('dia');
            $mensagemPeriodica->empresa_id = $userActual->empresa_id;
            $mensagemPeriodica->furo_id = $userActual->furo_id;

            if ($mensagemPeriodica->save()) {

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Mensagem Registado Com Sucesso']);

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
