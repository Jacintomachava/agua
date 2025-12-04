<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function senhaIndex()
    {
        // Retorna a view com os dados carregados
        return view('user.senha.create', [
        ]);
    }

    public function senhaUpdate(Request $request)
    {
        try {

            DB::beginTransaction();
            $userActual = Auth::user();

            // Actualizar User
            $user = User::where('id', $userActual->id)->first();
            $user->password = bcrypt($request->input('novaSenha'));

            if ($user->save()) {
                DB::commit();

                return response()->json(['status' => 1, 'message' => 'Senha Alterada com Sucesso!']);
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
}
