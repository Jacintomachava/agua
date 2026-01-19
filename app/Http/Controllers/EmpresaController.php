<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\UserEmpresa;
use App\Models\SaldoSMS;
use App\Models\User;
use App\Models\Ano;
use App\Models\Furo;
use App\Models\CoWork;
use App\Models\Provincia;
use App\Models\RoleUser;
use App\Models\UserFuro;
use App\Models\Subscricao;
use App\Models\Mensagem;
use App\Services\SMSService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    
    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            $todasEmpresas = Empresa::all();

            // Cria Empresa e colocar Saldo de SMSCredito
            $empresa = new Empresa();
            $empresa->nome = $request->input('nome_empresa');
            $empresa->nuit = $request->input('nuit');
            $empresa->telefone = $request->input('telefone_user');
            $empresa->distrito_id = $request->input('distrito');
            $empresa->endereco = $request->input('bairro');
            $empresa->valor_por_cliente = 35;
            $empresa->codigo = str_pad(count($todasEmpresas) + 1, 2, '0', STR_PAD_LEFT);

            $anoActual = Carbon::now()->year;
            $ano = Ano::where('ano',$anoActual)->first();

            if ($request->hasFile('logotipo')) {

                $arquivo = $request->file('logotipo');
                // Pega somente a extensão (ex: png, jpg)
                $extensao = $arquivo->getClientOriginalExtension();
                // Gera nome NOVO usando o NUIT
                $nomeArquivo = $request->input('nuit') . '.' . $extensao;
                // Caminho onde será salvo: public/logotipos
                $destino = public_path('logotipo');
                // Cria a pasta se não existir
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }
                // Move o arquivo para a pasta public
                $arquivo->move($destino, $nomeArquivo);
                // Salva o nome ou caminho no banco
                $empresa->logotipo = $nomeArquivo;
            }

            if ($empresa->save()) {

                $smsCredito = new SaldoSMS();
                $smsCredito->empresa_id = $empresa->id;

                // Cria Empresa e colocar Saldo de SMSCredito
                $furo = new Furo();
                $furo->nome = $request->input('nome_empresa');
                $furo->empresa_id = $empresa->id;
                $furo->endereco = $request->input('bairro');

                if($furo->save() && $smsCredito->save()){

                    $data = Carbon::now();
                    $codigo = rand(100000, 999999);
                    $smsDescricao = "Caro(a) ".$request->input('nome_user').", a sua empresa ".$request->input('nome_empresa')." foi criada, dados de acesso: user: ".$request->input('telefone_user')." senha: ".$codigo;

                    $user = new User();
                    $user->nome = $request->input('nome_user');
                    $user->telefone = $request->input('telefone_user');
                    $user->distrito_id = $request->input('distrito');
                    $user->password = bcrypt($codigo);
                    $user->empresa_id = $empresa->id;
                    $user->furo_id = $furo->id;

                    //Gerar SMS de Factura
                    $sms = new Mensagem();
                    $sms->descricao = $smsDescricao;
                    $sms->telefone = $request->input('telefone_user');
                    $sms->nome = $request->input('nome_user');
                    $sms->qtd = SMSService::quantidadeSMS($smsDescricao);
                    $sms->credito = SMSService::quantidadeSMS($smsDescricao)*1.8;
                    $sms->custo_real = SMSService::quantidadeSMS($smsDescricao)*1.2;
                    $sms->empresa_id = 1;
                    $sms->furo_id = 1;
                    $sms->data_envio = $data;

                    if($user->save() && $sms->save()){

                        $roleUser = new RoleUser();
                        $roleUser->role_id = 1;
                        $roleUser->model_type = 'App\Models\User';
                        $roleUser->model_id = $user->id;

                        $userFuro = new UserFuro();
                        $userFuro->furo_id = $furo->id;
                        $userFuro->user_id = $user->id;

                        $userEmpresa = new UserEmpresa();
                        $userEmpresa->user_id = $user->id;
                        $userEmpresa->empresa_id = $empresa->id;

                        if($roleUser->save() && $userFuro->save() && $userEmpresa->save()){

                            DB::commit();
                            return response()->json(['status' => 1, 'message' => 'Empresa Criada Com Sucesso']);

                        }


                    }

                }

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

    public function storeCoWork(Request $request)
    {
        DB::beginTransaction();

        try {

            $totalEmpresas = Empresa::all();
            $userActual = Auth::user();
            $codigoEmpresa = str_pad(count($totalEmpresas) + 1, 2, '0', STR_PAD_LEFT);
            // Cria Empresa e colocar Saldo de SMSCredito
            $empresa = new Empresa();
            $empresa->nome = $request->input('nome');
            $empresa->nuit = $request->input('nuit');
            $empresa->telefone = $request->input('telefone_utilizador');
            $empresa->distrito_id = $request->input('distrito');
            $empresa->endereco = $request->input('bairro');
            $empresa->valor_por_cliente = $request->input('preco');
            $empresa->codigo = $codigoEmpresa;

            $anoActual = Carbon::now()->year;
            $ano = Ano::where('ano',$anoActual)->first();

            if ($request->hasFile('logotipo')) {

                $arquivo = $request->file('logotipo');
                // Pega somente a extensão (ex: png, jpg)
                $extensao = $arquivo->getClientOriginalExtension();
                // Gera nome NOVO usando o NUIT
                $nomeArquivo = $request->input('nuit') . '.' . $extensao;
                // Caminho onde será salvo: public/logotipos
                $destino = public_path('logotipo');
                // Cria a pasta se não existir
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }
                // Move o arquivo para a pasta public
                $arquivo->move($destino, $nomeArquivo);
                // Salva o nome ou caminho no banco
                $empresa->logotipo = $nomeArquivo;
            }

            if ($empresa->save()) {

                $smsCredito = new SaldoSMS();
                $smsCredito->empresa_id = $empresa->id;

                // Cria Empresa e colocar Saldo de SMSCredito
                $furo = new Furo();
                $furo->nome = $request->input('nome');
                $furo->empresa_id = $empresa->id;
                $furo->endereco = $request->input('bairro');

                $coWork = new CoWork();
                $coWork->empresa_id = $empresa->id;
                $coWork->user_id = $userActual->id;
                $coWork->percentagem = $request->input('preco');

                if($furo->save() && $smsCredito->save() && $coWork->save()){

                    $data = Carbon::now();
                    $codigo = rand(100000, 999999);
                    $smsDescricao = "Caro(a) ".$request->input('nome_utilizador').", a sua empresa ".$request->input('nome')." foi criada, dados de acesso: user: ".$request->input('telefone_utilizador')." senha: ".$codigo;

                    $user = new User();
                    $user->nome = $request->input('nome_utilizador');
                    $user->telefone = $request->input('telefone_utilizador');
                    $user->distrito_id = $request->input('distrito');
                    $user->password = bcrypt($codigo);
                    $user->empresa_id = $empresa->id;
                    $user->furo_id = $furo->id;

                    //Gerar SMS de Factura
                    $sms = new Mensagem();
                    $sms->descricao = $smsDescricao;
                    $sms->telefone = $request->input('telefone_utilizador');
                    $sms->nome = $request->input('nome_utilizador');
                    $sms->qtd = SMSService::quantidadeSMS($smsDescricao);
                    $sms->credito = SMSService::quantidadeSMS($smsDescricao)*1.8;
                    $sms->custo_real = SMSService::quantidadeSMS($smsDescricao)*1.2;
                    $sms->empresa_id = 1;
                    $sms->furo_id = 1;
                    $sms->data_envio = $data;

                    if($user->save() && $sms->save()){

                        $roleUser = new RoleUser();
                        $roleUser->role_id = 1;
                        $roleUser->model_type = 'App\Models\User';
                        $roleUser->model_id = $user->id;

                        $userFuro = new UserFuro();
                        $userFuro->furo_id = $furo->id;
                        $userFuro->user_id = $user->id;

                        if($roleUser->save() && $userFuro->save()){

                            DB::commit();
                            return response()->json(['status' => 1, 'message' => 'Empresa Criada Com Sucesso']);

                        }


                    }

                }

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

    //Registo da Empresa Com CoWork
    public function create()
    {
        $userActual = Auth::user();
        $provincias = Provincia::all();

        // Retorna a view com os dados carregados
        return view('empresa.create', [
            'provincias' => $provincias,
        ]);
    }
}
