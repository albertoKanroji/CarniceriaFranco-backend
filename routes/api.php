<?php

use App\Http\Controllers\LogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\RutinasControllerAPI;
use App\Http\Controllers\GruposMuscularesControllerlAPI;
use App\Http\Controllers\PreguntasControllerAPI;
use App\Http\Controllers\NotificacionesController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::options('/{any}', function () {
    return response()->json([], 200, [
        'Access-Control-Allow-Origin' => '*', // Cambia '*' por tu dominio en producción
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'X-Requested-With, Content-Type, X-Token-Auth, Authorization',
    ]);
})->where('any', '.*');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => 'cors'], function () {

    Route::prefix('v1')->group(function () {

        Route::prefix('clientes')->group(function () {
            Route::get('/data', [CustomersController::class, 'getData'])->name('clientes.data');
            Route::put('/{id}', [CustomersController::class, 'update'])->name('clientes.update');
            Route::post('/login', [CustomersController::class, 'login'])->name('clientes.login');
            Route::post('/registro', [CustomersController::class, 'store'])->name('clientes.store');

            // Mantener rutas de imágenes si aún se usan
            Route::post('/imagenes', [CustomersController::class, 'storeImages'])->name('clientes.imagenes.store');
            Route::get('/{id}/imagenes', [CustomersController::class, 'listImages'])->name('clientes.imagenes.list');
            Route::delete('/{customerId}/imagenes/{imageId}', [CustomersController::class, 'deleteImage'])->name('clientes.imagenes.delete');
        });

        // Mantener alias para compatibilidad hacia atrás
        Route::prefix('usuarios')->group(function () {
            Route::get('/customer-data', [CustomersController::class, 'getData']);
            Route::put('/update/{id}', [CustomersController::class, 'update']);
            Route::post('/login', [CustomersController::class, 'login']);
            Route::post('/', [CustomersController::class, 'store']);
        });

        // Rutas de Categorías
        Route::prefix('categorias')->group(function () {
            Route::get('/', [CategoriesController::class, 'index'])->name('api.categorias.index');
            Route::get('/all', [CategoriesController::class, 'all'])->name('api.categorias.all');
            Route::get('/{id}', [CategoriesController::class, 'show'])->name('api.categorias.show');
            Route::get('/{id}/productos', [CategoriesController::class, 'getProducts'])->name('api.categorias.productos');
            Route::post('/', [CategoriesController::class, 'store'])->name('api.categorias.store');
            Route::put('/{id}', [CategoriesController::class, 'update'])->name('api.categorias.update');
        });

        // Rutas de Productos
        Route::prefix('productos')->group(function () {
            Route::get('/', [ProductsController::class, 'index'])->name('api.productos.index');
            Route::get('/destacados', [ProductsController::class, 'featured'])->name('api.productos.destacados');
            Route::get('/ofertas', [ProductsController::class, 'offers'])->name('api.productos.ofertas');
            Route::get('/buscar', [ProductsController::class, 'search'])->name('api.productos.buscar');
            Route::get('/categoria/{categoryId}', [ProductsController::class, 'byCategory'])->name('api.productos.categoria');
            Route::get('/{id}', [ProductsController::class, 'show'])->name('api.productos.show');
            Route::get('/{id}/stock', [ProductsController::class, 'checkStock'])->name('api.productos.stock');
            Route::post('/', [ProductsController::class, 'store'])->name('api.productos.store');
            Route::put('/{id}', [ProductsController::class, 'update'])->name('api.productos.update');
        });

        // Rutas de Ventas/Compras
        Route::prefix('ventas')->group(function () {
            // Crear una nueva compra
            Route::post('/', [SalesController::class, 'store'])->name('api.ventas.store');

            // Historial de compras del cliente
            Route::get('/cliente/{customerId}', [SalesController::class, 'getCustomerPurchases'])->name('api.ventas.cliente.historial');

            // Estadísticas de compras del cliente
            Route::get('/cliente/{customerId}/estadisticas', [SalesController::class, 'getCustomerStats'])->name('api.ventas.cliente.estadisticas');

            // Últimas compras del cliente
            Route::get('/cliente/{customerId}/recientes', [SalesController::class, 'getRecentPurchases'])->name('api.ventas.cliente.recientes');

            // Recomendaciones personalizadas para el cliente
            Route::get('/cliente/{customerId}/recomendaciones', [SalesController::class, 'getRecommendedPurchases'])->name('api.ventas.cliente.recomendaciones');

            // Todas las ventas (admin)
            Route::get('/', [SalesController::class, 'getAllSales'])->name('api.ventas.index');

            // Detalle de una compra específica
            Route::get('/{saleId}', [SalesController::class, 'getPurchaseDetail'])->name('api.ventas.detalle');

            // Cancelar una compra
            Route::put('/{saleId}/cancelar', [SalesController::class, 'cancelPurchase'])->name('api.ventas.cancelar');
        });

        // Rutas de Mercado Pago
        Route::prefix('mercadopago')->group(function () {
            // Ruta de prueba básica
            Route::get('/test', [App\Http\Controllers\MercadoPagoTestController::class, 'testBasic'])->name('api.mercadopago.test');

            // Crear preferencia de pago
            Route::post('/create-preference', [App\Http\Controllers\MercadoPagoController::class, 'createPreference'])->name('api.mercadopago.create-preference');

            // Webhook para notificaciones
            Route::post('/webhook', [App\Http\Controllers\MercadoPagoController::class, 'webhook'])->name('api.mercadopago.webhook');

            // URLs de redirección
            Route::get('/success', [App\Http\Controllers\MercadoPagoController::class, 'success'])->name('api.mercadopago.success');
            Route::get('/failure', [App\Http\Controllers\MercadoPagoController::class, 'failure'])->name('api.mercadopago.failure');
            Route::get('/pending', [App\Http\Controllers\MercadoPagoController::class, 'pending'])->name('api.mercadopago.pending');
        });



        Route::prefix('tags')->group(function () {

            Route::get('/', [GruposMuscularesControllerlAPI::class, 'getTags']);
        });
        Route::prefix('notificaciones')->group(function () {

            Route::get('/', [NotificacionesController::class, 'index']);
        });
        Route::prefix('historial')->group(function () {

            Route::post('/guardar-accion', [LogController::class, 'store']);
        });

    });
});
