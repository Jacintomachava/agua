<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tubagem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MapaController extends Controller
{
    //
    public function index()
    {
        $userActual = Auth::user();

        $empresa = $userActual->empresa_id;

        $tubagens = Tubagem::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->get();

        return view('tubagem.index',  [
               'tubagens' => $tubagens,
               'empresa' => $empresa,
        ]);
    }

    public function store(Request $request)
    {
        $userActual = Auth::user();

        DB::table('tubagem')->insert([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'empresa_id' => $userActual->empresa_id,
            'furo_id' => $userActual->furo_id,
            'ordem' => $request->ordem
        ]);

        return response()->json(['ok' => true]);
    }

    public function edit()
    {
        $userActual = Auth::user();

        $tubagem = DB::table('tubagem')->where('empresa_id', $request->empresa)->where('furo_id',$userActual->furo_id)->orderBy('ordem')->get();
        
        return view("tubagem.editar", compact('tubagem'));
    }

     public function delete(Request $request)
    {
        $userActual = Auth::user();

        // Apaga a tubagem anterior e grava a nova
        Tubagem::where('empresa_id', $request->empresa)->where('furo_id',$userActual->furo_id)->delete();

        return response()->json(['status' => 1, 'message' => 'Tubo Geral Apagado com Sucesso']);
    }

    // Salvar tubagem editada
    public function update(Request $request)
    {
        $user = Auth::user();
        $empresa = $user->empresa_id;
        $furo = $user->furo_id;

        $path = $request->path;

        if (!is_array($path) || count($path) == 0) {
            return response()->json(['error' => 'Nenhum ponto recebido'], 422);
        }

        // Apaga a tubagem anterior e grava a nova
        Tubagem::where('empresa_id', $empresa)->where('furo_id',$user->furo_id)->delete();

        foreach ($path as $i => $p) {
            Tubagem::create([
                'empresa_id' => $empresa,
                'furo_id' => $furo,
                'ordem'      => $i + 1,
                'latitude'   => $p['lat'],
                'longitude'  => $p['lng'],
                'diametro'   => $request->diametro ?? 32,
            ]);
        }

        return response()->json(['status' => 'ok']);
    }
}
