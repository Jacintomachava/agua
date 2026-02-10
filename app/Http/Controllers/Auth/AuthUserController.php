<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Provincia;

class AuthUserController extends Controller
{
    
    public function preRegisto(Request $request)
    {
       $provincias = Provincia::all();

       $coworkCode = $request->query('cowork'); // pode ser null

        return view('pre_registo',  [
             'provincias' => $provincias,
             'coworkCode' => $coworkCode,
        ]);
    }

    public function login()
    {
        return view('login');
    }

    public function logar(Request $request)
    {

        // Tenta autenticar o usuário com email, senha e estado ativo
        if (Auth::attempt(['telefone' => $request['telefone'], 'password' => $request['senha'], 'estado' => true])) {

            $user = Auth::user();

            return response()->json(['status' => 1,'tipo'=> $user->tipo, 'message' => 'Autenticado com Sucesso']);
        }

        // Se o usuário não existir
        return response()->json(['status' => 0,  'message' => 'Utilizador ou Senha Errada']);
    }
}
