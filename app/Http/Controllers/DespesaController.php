<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Despesa;
use App\Models\Categoria;
use Carbon\Carbon;
use App\Services\SMSService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DespesaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $categorias = Categoria::all();
        $despesas = Despesa::where('empresa_id', $user->empresa_id)->where('furo_id', $user->furo_id)->get();

        return view('despesas.index', compact(
            'categorias',
            'despesas',
        ));
    }

    public function create()
    {
        $user = Auth::user();

        $categorias = Categoria::all();

        return view('despesas.create', compact(
            'categorias',
        ));
    }

    public function edit($id)
    {
        $user = Auth::user();

        $categorias = Categoria::all();
        $despesa = Despesa::where('id',$id)->where('empresa_id',$user->empresa_id)->first();

        return view('despesas.edit', compact(
            'categorias',
            'despesa'
        ));
    }

    public function destroy($id)
    {
        try {

            $userActual = Auth::user();
            $despesa = Despesa::where('id',$id)->where('empresa_id',$userActual->empresa_id)->first();

            $despesa->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Contrato apagado com sucesso'
            ]);


        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            $user = Auth::user();

            // Cria Empresa e colocar Saldo de SMSCredito
            $despesa =new Despesa();
            $despesa->descricao = $request->input('descricao');
            $despesa->valor_despesa = $request->input('valor_despesa');
            $despesa->estado = $request->input('estado');
            $despesa->valor_pago = $request->input('valor_pago');
            $despesa->data_pagamento = $request->input('data_pagamento');
            $despesa->empresa_id = $user->empresa_id;
            $despesa->furo_id = $user->furo_id;
            $despesa->categoria_id = $request->input('categoria');

            if ($despesa->save()) {

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Despesa Registado Com Sucesso']);

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

    public function update(Request $request)
    {

        DB::beginTransaction();

        try {

            $user = Auth::user();

            // Cria Empresa e colocar Saldo de SMSCredito
            $despesa = Despesa::where('id',$request->input('id'))->first();
            $despesa->descricao = $request->input('descricao');
            $despesa->valor_despesa = $request->input('valor_despesa');
            $despesa->estado = $request->input('estado');
            $despesa->valor_pago = $request->input('valor_pago');
            $despesa->data_pagamento = $request->input('data_pagamento');
            $despesa->empresa_id = $user->empresa_id;
            $despesa->furo_id = $user->furo_id;
            $despesa->categoria_id = $request->input('categoria');

            if ($despesa->save()) {

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Despesa Actualizado Com Sucesso']);

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
