<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\DashbordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserFuroController;
use App\Http\Controllers\FuroController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\LeituraController;
use App\Http\Controllers\PagamentoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Auth::routes();

// Autenticacao
Route::post('/autenticar', [AuthUserController::class, 'logar'])->name('autenticacao');
Route::get('/pre-registo', [AuthUserController::class, 'preRegisto'])->name('pre_registo');
Route::get('/', [AuthUserController::class, 'login'])->name('welcame');
Route::get('/', [AuthUserController::class, 'login'])->name('login');

// Recuperar Senha
Route::get('/recuperar/senha', [UserController::class, 'recuperarSenhaIndex'])->name('recuperarSenha.index');
Route::post('/nova/senha', [UserController::class, 'recuperarSenhaTelefone'])->name('recuperarSenha.telefone');
Route::get('/repor/senha/{user_id}', [UserController::class, 'reporSenha'])->name('repor.senha');

//Empresa Gravar
Route::post('/registar/empresa', [EmpresaController::class, 'store'])->name('empresa.store');

//Home
Route::get('/home', [DashbordController::class, 'indexHome'])->name('dashbord.indexHome');

//Utilizadores
Route::get('/nivel', [RoleController::class, 'index'])->name('nivel.index');
Route::get('/user/furos', [UserFuroController::class, 'index'])->name('userFuro.index');
Route::get('/user/furo', [UserFuroController::class, 'create'])->name('userFuro.create');
Route::post('/user/furo', [UserFuroController::class, 'store'])->name('userFuro.store');

//Furos
Route::get('/furos', [FuroController::class, 'index'])->name('furo.index');
Route::get('/registar/furo', [FuroController::class, 'create'])->name('furo.create');
Route::post('/registar/furo', [FuroController::class, 'store'])->name('furo.store');

//Tipos de Contrato
Route::get('/contratos', [ContratoController::class, 'index'])->name('contrato.index');
Route::get('/contrato', [ContratoController::class, 'create'])->name('contrato.create');
Route::post('/contrato', [ContratoController::class, 'store'])->name('contrato.store');

//Cliente
Route::get('/clientes', [ClienteController::class, 'index'])->name('cliente.index');
Route::get('/cliente', [ClienteController::class, 'create'])->name('cliente.create');
Route::post('/cliente', [ClienteController::class, 'store'])->name('cliente.store');
Route::get('/meus/clientes', [ClienteController::class, 'meuCliente'])->name('cliente.meuClientes');

//Leitura
Route::get('/leituras', [LeituraController::class, 'index'])->name('leituras.index');
Route::get('/leituras/{contratoID}', [LeituraController::class, 'geolocalizacao'])->name('cliente.geolocalizacao');
Route::post('/leituras/geolocalizacao', [LeituraController::class, 'geolocalizacaoStore'])->name('geolocalizacao.store');
Route::get('/leitura/{contratoID}', [LeituraController::class, 'leituraContador'])->name('leitura.contador');
Route::get('/cliente/leitura/{contratoID}', [LeituraController::class, 'edit'])->name('leitura.edit');
Route::post('/fazer/leitura', [LeituraController::class, 'update'])->name('leitura.update');
Route::get('/localizar/casa/{contratoID}', [LeituraController::class, 'localizarCasa'])->name('localizar.casa');

//Pagamento
Route::get('/pagamento/leitura', [PagamentoController::class, 'index'])->name('pagamento.index');
Route::get('/imprimir/factura', [PagamentoController::class, 'fatura'])->name('fatura.index');
Route::get('/pagamentos/leituras/{contratoID}', [PagamentoController::class, 'show'])->name('pagamento.show');
Route::post('/pagamentos', [PagamentoController::class, 'store'])->name('pagamentos.store');