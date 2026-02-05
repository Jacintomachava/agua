<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\UserFuro;
use App\Models\Furo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FuroController extends Controller
{
    public function index()
    {
        $userActual = Auth::user();

        $furos = Furo::where('empresa_id',$userActual->empresa_id)->get();

        return view('furos.index',  [
             'furos' => $furos,
        ]);
    }

    public function create()
    {
        return view('furos.create',  [

        ]);
    }

    public function mudarFuro()
    {
        $userActual = Auth::user();

        $furoAtribuidos = UserFuro::where('user_id',$userActual->id)->get();

        return view('mudarFuro.create',  [
                    'furoAtribuidos' => $furoAtribuidos,
        ]);
    }

    public function mudarFuroUpdate(Request $request)
    {
        try {
            DB::beginTransaction();

            // Actualizar User
            $user = User::where('codigo', $request->input('user'))->first();
            $user->furo_id = $request->input('escola');

            if ($user->save()) {
                // Cria a sessão com informações do usuário

                DB::commit();

                return response()->json(['status' => 1, 'message' => 'Furo mudada com Sucesso!']);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            // $errorMessage = DatabaseErrorHandler::handle($e);
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            $userActual = Auth::user();

            // Cria Empresa e colocar Saldo de SMSCredito
            $furo = new Furo();
            $furo->nome = $request->input('nome');
            $furo->empresa_id = $userActual->empresa_id;
            $furo->endereco = $request->input('endereco');

            if ($furo->save()) {

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Furo Registado Com Sucesso']);

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
