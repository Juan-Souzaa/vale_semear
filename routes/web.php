<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlanejamentoController;
use App\Http\Controllers\ReunioesController;
use App\Http\Controllers\RelatoriosController;
use App\Http\Controllers\AtaController;
use App\Http\Controllers\ObrigacaoController;
use App\Http\Controllers\DecisaoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserRoleController;

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

// Página inicial
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Rotas protegidas
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/dashboard/notificacoes', [DashboardController::class, 'getNotificacoes'])->name('dashboard.notificacoes');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Rotas de Planejamento (Atividades)
    Route::resource('planejamento', PlanejamentoController::class);
    
    // Rotas de Obrigações
    Route::resource('obrigacoes', ObrigacaoController::class)->parameters([
        'obrigacoes' => 'obrigacao'
    ]);
    
    // Rotas de Reuniões
    Route::resource('reunioes', ReunioesController::class)->parameters([
        'reunioes' => 'reuniao'
    ]);
    Route::post('/reunioes/{reuniao}/participantes', [ReunioesController::class, 'adicionarParticipante'])->name('reunioes.participantes.add');
    Route::delete('/reunioes/{reuniao}/participantes/{user}', [ReunioesController::class, 'removerParticipante'])->name('reunioes.participantes.remove');
    Route::post('/reunioes/{reuniao}/participantes/{user}/confirmar', [ReunioesController::class, 'confirmarPresenca'])->name('reunioes.participantes.confirmar');
    Route::post('/reunioes/{reuniao}/lembretes', [ReunioesController::class, 'salvarLembretes'])->name('reunioes.lembretes.save');
    Route::delete('/reunioes/{reuniao}/lembretes/{lembrete}', [ReunioesController::class, 'removerLembrete'])->name('reunioes.lembretes.remove');
    
    // Rotas de Atas
    Route::get('/reunioes/{reuniao}/atas/create', [AtaController::class, 'create'])->name('atas.create');
    Route::post('/reunioes/{reuniao}/atas', [AtaController::class, 'store'])->name('atas.store');
    Route::get('/atas/{ata}', [AtaController::class, 'show'])->name('atas.show');
    Route::get('/atas/{ata}/edit', [AtaController::class, 'edit'])->name('atas.edit');
    Route::put('/atas/{ata}', [AtaController::class, 'update'])->name('atas.update');
    Route::post('/atas/{ata}/aprovar', [AtaController::class, 'aprovar'])->name('atas.aprovar');
    
    // Rotas de Decisões
    Route::get('/reunioes/{reuniao}/decisoes/create', [DecisaoController::class, 'create'])->name('decisoes.create');
    Route::post('/reunioes/{reuniao}/decisoes', [DecisaoController::class, 'store'])->name('decisoes.store');
    Route::get('/decisoes/{decisao}', [DecisaoController::class, 'show'])->name('decisoes.show');
    Route::get('/decisoes/{decisao}/edit', [DecisaoController::class, 'edit'])->name('decisoes.edit');
    Route::put('/decisoes/{decisao}', [DecisaoController::class, 'update'])->name('decisoes.update');
    Route::delete('/decisoes/{decisao}', [DecisaoController::class, 'destroy'])->name('decisoes.destroy');
    
    Route::get('/relatorios', [RelatoriosController::class, 'index'])->name('relatorios');
    Route::get('/relatorios/exportar/{tipo}/{formato}', [RelatoriosController::class, 'exportar'])->name('relatorios.exportar');
    
    // Rotas de Permissões e Roles (protegidas por permissões)
    Route::middleware('permission:permissoes.view')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class)->only(['index']);
        Route::get('/users/{user}/roles', [UserRoleController::class, 'show'])->name('users.roles');
        Route::post('/users/{user}/roles/assign', [UserRoleController::class, 'assignRoles'])->name('users.roles.assign');
        Route::post('/users/{user}/permissions/assign', [UserRoleController::class, 'assignPermissions'])->name('users.permissions.assign');
    });
});
