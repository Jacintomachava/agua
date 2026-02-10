<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Furo;
use App\Models\FuroClienteContrato;
use App\Models\Distrito;
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

        $users = User::where('empresa_id',$userActual->empresa_id)->where('furo_id',$userActual->furo_id)->where('tipo','<>','Cliente')->get();

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

    public function edit($idUser)
    {
        $userActual = Auth::user();

        $user = User::where('id',$idUser)->where('empresa_id',$userActual->empresa_id)->first();

        $furos = Furo::where('empresa_id',$userActual->empresa_id)->get();
        $roles = Role::where('fornecedor_agua', 1)->get();
        $provincias = Provincia::all();

        $userRoles = $user->roles->pluck('name')->toArray();
        $userFuros = UserFuro::where('user_id',$user->id)->pluck('furo_id')->toArray();
        $distritos = Distrito::where('provincia_id', $user->distrito->provincia->id)->get();

        return view('user.furos.edit',  [
            'roles' => $roles,
            'furos' => $furos,
            'provincias' => $provincias,
            'user' => $user,
            'userRoles' => $userRoles,
            'userFuros' => $userFuros,
            'distritos' => $distritos,
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

    public function update(Request $request)
    {

        DB::beginTransaction();

        try {

            $userActual = Auth::user();

            $userID = $request->input('user_id');
            $roles = $request->input('roles', []);
            $furos = $request->input('furos', []);

            // Cria Empresa e colocar Saldo de SMSCredito
            $user = User::findOrFail($request->user_id);
            $user->nome = $request->input('nome');
            $user->telefone = $request->input('telefone');
            $user->distrito_id = $request->input('distrito');
            //$user->password = bcrypt($request->input('telefone'));
            $user->empresa_id = $userActual->empresa_id;
            $user->furo_id = $userActual->furo_id;
            $user->mudar_furo = true;



            if ($user->save()) {

                $user->syncRoles($roles);

                // Remove todos furos antigos
                UserFuro::where('user_id', $user->id)->delete();

                // Insere novos
                foreach ($furos as $furoID) {

                    UserFuro::create([
                        'user_id' => $user->id,
                        'furo_id' => $furoID
                    ]);
                }

                DB::commit();
                return response()->json(['status' => 1, 'message' => 'Utilizador Actualizado Com Sucesso']);

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

    public function toggleEstado($id)
    {
        $user = User::findOrFail($id);

        $user->estado = $user->estado == 1 ? 0 : 1;
        $user->save();

        return response()->json([
            'status' => 1,
            'message' => 'Estado alterado com sucesso'
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $cliente = FuroClienteContrato::where('user_id',$user->id)->get();

        if($cliente==null)
        {
            $user->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Utilizador apagado com sucesso'
            ]);

        }else{

            return response()->json([
                'status' => 0,
                'message' => 'Utilizador Nao Apagado por ter Historico'
            ]);
        }

        
    }


}
