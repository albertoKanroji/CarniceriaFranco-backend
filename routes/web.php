<?php

use App\Http\Controllers\ExportController;
use App\Http\Livewire\Categorias\CategoriasController;
use App\Http\Livewire\Clientes\ClientesController;
use App\Http\Livewire\Dash;
use App\Http\Livewire\Despachos\DespachosController;
use App\Http\Livewire\Equipo\EquipoController;
use App\Http\Livewire\Logs\LogsController;
use App\Http\Livewire\Notificaciones\NotificacionesController;
use App\Http\Livewire\PermisosController;
use App\Http\Livewire\Productos\ProductosController;
use App\Http\Livewire\RolesController;
use App\Http\Livewire\UsersController;
use App\Http\Livewire\Ventas\VentasController;
use App\Http\Livewire\Sitio\SitioController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

//Auth::routes();

Auth::routes(['register' => false]); // deshabilitamos el registro de nuevos users

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', Dash::class);

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('categorias', CategoriasController::class);
        Route::get('productos', ProductosController::class);
        Route::get('ventas', VentasController::class);
    });

    Route::prefix('clientes')->group(function () {
        Route::get('/', ClientesController::class);
        Route::get('despachos', DespachosController::class);
    });

    Route::prefix('sistema')->group(function () {
        Route::get('users', UsersController::class);
        Route::group(['middleware' => ['role:Admin']], function () {
            Route::get('roles', RolesController::class);
            Route::get('permisos', PermisosController::class);
            Route::get('notificaciones', NotificacionesController::class);

        });
        Route::get('sitio', SitioController::class);
    });

    Route::get('logs', LogsController::class);
    Route::get('equipo', EquipoController::class);

});
