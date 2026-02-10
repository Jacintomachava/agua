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
use App\Http\Controllers\MensagemController;
use App\Http\Controllers\MapaController;
use App\Http\Controllers\FinancaController;
use App\Http\Controllers\SubscricaoController;
use App\Http\Controllers\PagamentoSubscricaoController;
use App\Http\Controllers\CoWorkController;
use App\Http\Controllers\MensagemPeriodicaController;
use App\Http\Controllers\CredencialController;
use App\Http\Controllers\TempleteSMSController;
use App\Http\Controllers\ClienteFuroController;
use App\Http\Controllers\DashbordDonoController;
use App\Http\Controllers\DespesaController;

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
//Route::get('/', [AuthUserController::class, 'login'])->name('welcame');
Route::get('/', [AuthUserController::class, 'login'])->name('login');

// Recuperar Senha
Route::get('/recuperar/senha', [UserController::class, 'recuperarSenhaIndex'])->name('recuperarSenha.index');
Route::post('/nova/senha', [UserController::class, 'recuperarSenhaTelefone'])->name('recuperarSenha.telefone');
Route::get('/repor/senha/{user_id}', [UserController::class, 'reporSenha'])->name('repor.senha');
Route::get('/alterar/senha', [UserController::class, 'senhaIndex'])->name('senha.index');
Route::post('/editar/senha', [UserController::class, 'senhaUpdate'])->name('senha.update');

//Cliente Furro
Route::get('/cliente/furo', [ClienteFuroController::class, 'index'])->name('clienteFuro.index');

//DashBord dos Donos
Route::get('/dashbord', [DashbordDonoController::class, 'index'])->name('sasDashbord.index');
Route::get('/listar/empresas', [DashbordDonoController::class, 'listarEmpresas'])->name('listarEmpresas.index');
Route::get('/listar/coworks', [DashbordDonoController::class, 'listarCoworks'])->name('listarCoworks.index');
Route::get('/listar/pagamentos', [DashbordDonoController::class, 'listarPagamentos'])->name('listarPagamentos.index');

//Mensagens Periodicas
Route::get('/mensagem/templete', [TempleteSMSController::class, 'create'])->name('SMSperidica.create');
Route::post('/mensagem/templete', [TempleteSMSController::class, 'store'])->name('SMSperidica.store');
Route::put('/mensagens-periodicas/{id}/toggle', [TempleteSMSController::class, 'toggleEstado'])
    ->name('mensagens.toggle');

Route::get('/leituras/pendentes', [LeituraController::class, 'pendentes'])->name('leituras.pendentes');
Route::get('/todas/leituras', [LeituraController::class, 'todasLeituras'])->name('todas.leituras');
Route::get('/facturas/todos', [LeituraController::class, 'facturasTodos'])->name('facturas.todos');
Route::get('/facturas/leitura/{id}', [LeituraController::class, 'facturaLeitura'])->name('facturas.leitura');

//Empresa Gravar
Route::post('/registar/empresa', [EmpresaController::class, 'store'])->name('empresa.store');
//Empresa Co_work
Route::get('/empresa/cowork', [EmpresaController::class, 'create'])->name('empresa.create');
Route::post('/empresa/cowork', [EmpresaController::class, 'storeCoWork'])->name('empresa.storeCoWork');

//Home
Route::get('/home', [DashbordController::class, 'indexHome'])->name('dashbord.indexHome');

//Utilizadores
Route::get('/nivel', [RoleController::class, 'index'])->name('nivel.index');
Route::get('/user/furos', [UserFuroController::class, 'index'])->name('userFuro.index');
Route::get('/user/furo', [UserFuroController::class, 'create'])->name('userFuro.create');
Route::post('/user/furo', [UserFuroController::class, 'store'])->name('userFuro.store');
Route::get('/user/furo/{id}', [UserFuroController::class, 'edit'])->name('userFuro.edit');
Route::post('/user/furo/update', [UserFuroController::class, 'update'])->name('userFuro.update');
Route::post('/user/toggle-estado/{id}', [UserFuroController::class, 'toggleEstado']);
Route::delete('/user/delete/{id}', [UserFuroController::class, 'destroy']);


//Furos
Route::get('/furos', [FuroController::class, 'index'])->name('furo.index');
Route::get('/registar/furo', [FuroController::class, 'create'])->name('furo.create');
Route::post('/registar/furo', [FuroController::class, 'store'])->name('furo.store'); 
Route::post('/update/furo', [FuroController::class, 'update'])->name('furo.update');  
Route::get('/mudar/furo', [FuroController::class, 'mudarFuro'])->name('mudar.furo');
Route::post('/mudars/furos', [FuroController::class, 'mudarFuroUpdate'])->name('mudar1.furo');
Route::get('/furo/edit/{id}', [FuroController::class, 'edit'])->name('furo.edit');
Route::delete('/furo/delete/{id}', [FuroController::class, 'destroy'])->name('furo.destroy');


//Tipos de Contrato
Route::get('/contratos', [ContratoController::class, 'index'])->name('contrato.index');
Route::get('/contrato', [ContratoController::class, 'create'])->name('contrato.create');
Route::post('/contrato', [ContratoController::class, 'store'])->name('contrato.store');
Route::post('/update/contrato', [ContratoController::class, 'update'])->name('contrato.update');
Route::get('/contrato/{id}', [ContratoController::class, 'edit'])->name('contrato.edit');
Route::delete('/contrato/delete/{id}', [ContratoController::class, 'destroy'])->name('contrato.destroy');

//templete Contracto
Route::get('/templete/contrato', [ContratoController::class, 'templete'])->name('contrato.templete');
Route::post('/contrato/templete', [ContratoController::class, 'registarTemplete'])->name('templete.contrato');
Route::get('/contracto/cliente/{codigo}', [ContratoController::class, 'contratoCliente'])->name('contrato.cliente');
Route::get('/editar/templete/', [ContratoController::class, 'contratoTemplete'])->name('contrato.editarTemplete');
Route::post('/update/templete/', [ContratoController::class, 'updateTemplete'])->name('update.templete');

//Cliente
Route::get('/clientes', [ClienteController::class, 'index'])->name('cliente.index');
Route::get('/cliente', [ClienteController::class, 'create'])->name('cliente.create');
Route::get('/actualizar/cliente/{codigo}', [ClienteController::class, 'edit'])->name('cliente.edit');
Route::post('/update/cliente', [ClienteController::class, 'update'])->name('cliente.update');
Route::post('/cliente', [ClienteController::class, 'store'])->name('cliente.store');
Route::get('/meus/clientes', [ClienteController::class, 'meuCliente'])->name('cliente.meuClientes');
Route::get('/detalhe/cliente/{codigo}', [ClienteController::class, 'show'])->name('cliente.show');
Route::get('/cortar/agua/{codigo}', [ClienteController::class, 'cortar'])->name('cliente.cortar');
Route::get('/ligar/agua/{codigo}', [ClienteController::class, 'ligar'])->name('cliente.ligar');
Route::post('/registar/cortar', [ClienteController::class, 'registarCortar'])->name('cliente.registarCortar');
Route::post('/registar/ligar', [ClienteController::class, 'registarLigar'])->name('cliente.registarLigacao');
Route::get('/localizcao/clientes', [ClienteController::class, 'mapa'])->name('mapa.clientes');
//PDF Clientes
Route::get('/pdf/clientes', [ClienteController::class, 'PDFCliente'])->name('pdf.clientes');

//Leitura
Route::get('/leituras', [LeituraController::class, 'index'])->name('leituras.index');
Route::get('/leituras/{contratoID}', [LeituraController::class, 'geolocalizacao'])->name('cliente.geolocalizacao');
Route::post('/leituras/geolocalizacao', [LeituraController::class, 'geolocalizacaoStore'])->name('geolocalizacao.store');
Route::get('/leitura/{contratoID}', [LeituraController::class, 'leituraContador'])->name('leitura.contador');
Route::get('/cliente/leitura/{contratoID}', [LeituraController::class, 'edit'])->name('leitura.edit');
Route::post('/fazer/leitura', [LeituraController::class, 'update'])->name('leitura.update');
Route::get('/localizar/casa/{contratoID}', [LeituraController::class, 'localizarCasa'])->name('localizar.casa');
Route::get('/leitura/fatura/{leituraID}', [LeituraController::class, 'fatura'])->name('leitura.fatura');
//Extractos Pagamento Leitura
Route::get('/extracto/cliente/{codigo}', [LeituraController::class, 'extracto'])->name('extracto.cliente');

//Pagamento
Route::get('/pagamento/leitura', [PagamentoController::class, 'index'])->name('pagamento.index');
Route::get('/pagamento/leitura/{id}', [PagamentoController::class, 'reciboLeitura'])->name('recibo.leitura');
Route::get('/imprimir/factura', [PagamentoController::class, 'fatura'])->name('fatura.index');
Route::get('/pagamentos/leituras/{contratoID}', [PagamentoController::class, 'show'])->name('pagamento.show');
Route::post('/pagamentos', [PagamentoController::class, 'store'])->name('pagamentos.store');
Route::post('/pagamento', [PagamentoController::class, 'storeParcial'])->name('pagamento.storeParcial');
Route::get('/pagamento/leituras/{contratoID}', [PagamentoController::class, 'showParcial1'])->name('pagamentos.showParcial');
//Pagamento de Subscricao
Route::post('/pagamento/subscricao', [PagamentoSubscricaoController::class, 'store'])->name('pagamentoSubscricao.store');
Route::get('/pagamento/mensalidade/{factura}', [PagamentoSubscricaoController::class, 'show'])->name('pagamentoSubscricao.show');

// Mensagem
Route::get('/mensagem', [MensagemController::class, 'index'])->name('mensagem.index');
Route::get('/mensagem/{contacto}', [MensagemController::class, 'show'])->name('mensagem.show');
Route::post('/mensagem', [MensagemController::class, 'store'])->name('mensagem.store');
Route::post('/enviar/mensagem', [MensagemController::class, 'storeSMS'])->name('mensagem.storeSMS');
Route::get('/escrever/mensagem', [MensagemController::class, 'create'])->name('mensagem.create');
Route::post('/comprar/credito', [MensagemController::class, 'storeCompraSMS'])->name('mensagem.storeCompraSMS');
Route::post('/comprar/saldo', [MensagemController::class, 'storeCompraSaldo'])->name('mensagem.storeCompraSMS1');

//Mapa Tubagem 
Route::get('/mapa/tubagem', [MapaController::class, 'index'])->name('mapa.index');
Route::post('/rota/tubagem', [MapaController::class, 'store'])->name('mapa.store');
Route::post('/delete/tubagem', [MapaController::class, 'delete'])->name('mapa.delete');
Route::post('/rota/tubagem/update', [MapaController::class, 'update'])->name('mapa.update');

//Despesas
Route::get('/despesas', [DespesaController::class, 'index'])->name('despesas.index');
Route::get('/registar/despesas', [DespesaController::class, 'create'])->name('despesas.create');
Route::post('/despesas', [DespesaController::class, 'store'])->name('despesas.store');
Route::get('/despesa/{id}', [DespesaController::class, 'edit'])->name('despesas.edit');
Route::post('/despesa/update', [DespesaController::class, 'update'])->name('despesas.update');
Route::get('/despesa/apagar/{id}', [DespesaController::class, 'delete'])->name('despesas.delete');

//Financas
Route::get('/financas', [FinancaController::class, 'index'])->name('financas.index');

//Subscricao
Route::get('/mensalidades/sistema', [SubscricaoController::class, 'index'])->name('subscricao.index');

//CoWork
Route::get('/dashbord/cowork', [CoWorkController::class, 'index'])->name('cowork.index');
Route::get('/minhas/empresas', [CoWorkController::class, 'minhasEmpresas'])->name('minhas.empresas');
Route::get('/minhas/mensalidades', [CoWorkController::class, 'minhasMensalidades'])->name('minhas.mensalidades');
Route::get('/credito/mensagem', [CoWorkController::class, 'creditoMensagem'])->name('credito.mensagem');
Route::get('/levantamentos', [CoWorkController::class, 'levantamento'])->name('levantamento');
Route::post('/fazer/levantamento', [CoWorkController::class, 'fazerLevantamento'])->name('fazer.levantamento');

//Credencial
Route::get('/credencial/mpesa', [CredencialController::class, 'index'])->name('credencial.index');
Route::get('/registar/credencial', [CredencialController::class, 'create'])->name('credencial.create');
Route::post('/registar/credenciais', [CredencialController::class, 'store'])->name('credencial.store');

