<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Provincia;
use App\Models\User;

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

    public function loginApp(Request $request)
    {
        $request->validate([
            'telefone' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('telefone', $request->telefone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Credenciais inválidas'
            ], 401);
        }

        $token = $user->createToken('app_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'nome' => $user->nome,
                'perfil' => $user->tipo,
                'furo_id' => $user->furo_id,
                'furo_nome' => optional($user->furo)->nome
            ]
        ]);
    }
}
