<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credencial;
use App\Models\Empresa;
use App\Services\SMSService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CredencialController extends Controller
{

    public function index()
    {
        $userActual = Auth::user();
        $credenciais = Credencial::where('empresa_id',$userActual->empresa_id)->get();
        
        return view('credencial.index',  [
             'credenciais' => $credenciais,
        ]);
    }

    public function create()
    {
        $userActual = Auth::user();
        
        return view('credencial.create',  [

        ]);
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            $userActual = Auth::user();
            $empresa = Empresa::where('id',$userActual->empresa_id)->first();
            // Cria Empresa e colocar Saldo de SMSCredito
            $credencial = new Credencial();
            $credencial->api_key = $request->input('api_key');
            $credencial->empresa_id = $userActual->empresa_id;
            $credencial->public_key = $request->input('public_key');
            $credencial->service_provaider_code = $request->input('service_code');
            $credencial->env = $request->input('ambiente');

            $empresa->mpesa_activo = true;


            if ($credencial->save() && $empresa->save()) {

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Credencial Registado Com Sucesso']);

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
