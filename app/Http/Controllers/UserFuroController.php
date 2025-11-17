<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Furo;
use App\Models\Provincia;
use App\Models\RoleUser;
use App\Models\UserFuro;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class UserFuroController extends Controller
{
    public function index()
    {
        $userActual = Auth::user();

        $users = User::where('empresa_id',$userActual->empresa_id)->get();

        return view('user.furos.index',  [
             'users' => $users,
        ]);
    }

    public function create()
    {
        $userActual = Auth::user();

        $furos = Furo::where('empresa_id',$userActual->empresa_id)->get();
        $roles = Role::where('fornecedor_agua', 1)->get();
        $provincias = Provincia::all();

        return view('user.furos.create',  [
            'roles' => $roles,
            'furos' => $furos,
            'provincias' => $provincias,
        ]);
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            $userActual = Auth::user();

            $roles = $request->input('roles', []);
            $furos = $request->input('furos', []);

            // Cria Empresa e colocar Saldo de SMSCredito
            $user = new User();
            $user->nome = $request->input('nome');
            $user->telefone = $request->input('telefone');
            $user->distrito_id = $request->input('distrito');
            $user->password = bcrypt($request->input('telefone'));
            $user->empresa_id = $userActual->empresa_id;
            $user->mudar_furo = true;

            if ($user->save()) {

                foreach ($roles as $alunoID) {
                    $role = Role::where('name', $alunoID)->first();

                    $roleUser = new RoleUser();
                    $roleUser->role_id = $role->id;
                    $roleUser->model_type = 'App\Models\User';
                    $roleUser->model_id = $user->id;
                    $roleUser->save();
                }

                foreach ($furos as $alunoID) {
                    $furo = Furo::where('id', $alunoID)->first();

                    $userFuro = new UserFuro();
                    $userFuro->furo_id = $furo->id;
                    $userFuro->user_id = $user->id;
                    $roleUser->save();
                }

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'utilizador Registado Com Sucesso']);

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
