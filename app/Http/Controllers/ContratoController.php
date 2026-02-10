<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrato;
use App\Models\ContractoTemplete;
use App\Models\FuroClienteContrato;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ContratoController extends Controller
{
    public function index()
    {
        $userActual = Auth::user();

        $contratos = Contrato::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();
        $contrato = ContractoTemplete::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->first();

        return view('contratos.index',  [
             'contratos' => $contratos,
             'contrato' => $contrato,
        ]);
    }

    public function templete()
    {
        $userActual = Auth::user();

        $contrato = ContractoTemplete::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->first();

        return view('contratos.templete',  [
            'contrato' => $contrato,
        ]);
    }

    public function contratoTemplete()
    {
        $userActual = Auth::user();

        $contrato = ContractoTemplete::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->first();

        return view('contratos.editTemplete',  [
            'contrato' => $contrato,
        ]);
    }

    public function create()
    {
        return view('contratos.create',  [

        ]);
    }

    public function edit($id)
    {
        $userActual = Auth::user();

        $contrato = Contrato::where('id',$id)->where('empresa_id',$userActual->empresa_id)->first();

        return view('contratos.edit',  [
                'contacto' => $contrato,
        ]);
    }

    public function contratoCliente($codigo)
    {
        $userActual = Auth::user();

        $cliente = FuroClienteContrato::where('empresa_id',$userActual->empresa_id)->where('codigo', $codigo)->first();
        $contrato = ContractoTemplete::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->first();

        // Conteúdo do template
        $texto = $contrato->conteudo;

        // Dados reais
        $dados = [
            '{{cliente_nome}}' => $cliente->cliente->nome,
            '{{cliente_documento}}' => $cliente->cliente->tipo_documento,
            '{{cliente_documento_numero}}' => $cliente->cliente->numero_documento,
            '{{cliente_nacionalidade}}' => 'Mocambicana',
            '{{cliente_documento_entidade_emissora}}' => 'Maputo',
            '{{cliente_documento_data_emissao}}' => now()->format('d-m-Y'),
            '{{cliente_bairro}}' => $cliente->bairro,
            '{{cliente_quarteirao}}' => $cliente->quarteirao,
            '{{cliente_casa}}' => $cliente->casa,
            '{{cliente_telefone}}' => $cliente->telefone_notificar,
        ];

        // Substituir
        $conteudoFinal = str_replace(array_keys($dados), array_values($dados), $texto);

        $pdf = \PDF::loadView('contratos.pdf', [
             'conteudo' => $conteudoFinal
        ])->setPaper('a4', 'Portrait');

        $fikeName = 'Contracto - '.$cliente->cliente->nome;

        return $pdf->stream($fikeName.'.pdf');

    }

    public function updateTemplete(Request $request)
    {
        DB::beginTransaction();

        try {

            $userActual = Auth::user();
            // Cria Empresa e colocar Saldo de SMSCredito
            $contrato = ContractoTemplete::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->first();
            $contrato->empresa_id = $userActual->empresa_id;
            $contrato->furo_id = $userActual->furo_id;
            $contrato->conteudo = $request->conteudo;

            if ($contrato->save()) {

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Templete Contrato Registado Com Sucesso']);

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

    public function registarTemplete(Request $request)
    {

        DB::beginTransaction();

        try {

            $userActual = Auth::user();

            // Cria Empresa e colocar Saldo de SMSCredito
            $contrato = new ContractoTemplete();
            $contrato->empresa_id = $userActual->empresa_id;
            $contrato->furo_id = $userActual->furo_id;
            $contrato->conteudo = $request->conteudo;

            if ($contrato->save()) {

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Templete Contrato Registado Com Sucesso']);

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

    public function destroy($id)
    {
        try {

            $userActual = Auth::user();
            $contrato = Contrato::findOrFail($id);
            $cliente = FuroClienteContrato::where('contrato_id',$id)->where('empresa_id',$userActual->empresa_id)->first();

            if($cliente==null){

                 $contrato->delete();

                return response()->json([
                    'status' => 1,
                    'message' => 'Contrato apagado com sucesso'
                ]);

            }else{

                return response()->json([
                    'status' => 0,
                    'message' => 'Contrato com historico nao pode ser apagado'
                ]);
            }

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

            $userActual = Auth::user();

            // Cria Empresa e colocar Saldo de SMSCredito
            $contrato = new Contrato();
            $contrato->consumo_minimo = $request->input('consumo_minimo');
            $contrato->valor_contrato = $request->input('valor_contrato');
            $contrato->valor = $request->input('valor_consumo');
            $contrato->nome = $request->input('nome');
            $contrato->prazo_pagamento = $request->input('prazo_pagamento');
            $contrato->metro_cubico = $request->input('consumo');
            $contrato->multa = $request->input('multa');
            $contrato->empresa_id = $userActual->empresa_id;
            $contrato->furo_id = $userActual->furo_id;

            if ($contrato->save()) {

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Contrato Registado Com Sucesso']);

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

            $userActual = Auth::user();

            // Cria Empresa e colocar Saldo de SMSCredito
            $contrato = Contrato::where('id',$request->input('id'))->where('empresa_id',$userActual->empresa_id)->first();
            $contrato->consumo_minimo = $request->input('consumo_minimo');
            $contrato->valor_contrato = $request->input('valor_contrato');
            $contrato->valor = $request->input('valor_consumo');
            $contrato->nome = $request->input('nome');
            $contrato->prazo_pagamento = $request->input('prazo_pagamento');
            $contrato->metro_cubico = $request->input('consumo');
            $contrato->multa = $request->input('multa');
            $contrato->empresa_id = $userActual->empresa_id;
            $contrato->furo_id = $userActual->furo_id;

            if ($contrato->save()) {

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Contrato Actualizado Com Sucesso']);

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
