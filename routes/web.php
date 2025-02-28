<?php

use App\Http\Controllers\Master\ProduksiController;
use App\Http\Controllers\Transaksi\ItemController;
use App\Http\Controllers\Transaksi\MutasiController;
use App\Http\Controllers\Setting\RoleController;
use App\Http\Controllers\Setting\PermissionController;
use App\Http\Controllers\Setting\UserManagementController;
use App\Http\Controllers\Setting\MenuController;
use App\Http\Controllers\Setting\ApprovalRouteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('users')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/', [UserManagementController::class, 'index'])->name('users.index')->middleware('menu.permission:index');
    Route::get('/create', [UserManagementController::class, 'create'])->name('users.create')->middleware('menu.permission:create');
    Route::post('/store', [UserManagementController::class, 'store'])->name('users.store')->middleware('menu.permission:store');
    Route::get('/edit/{user}', [UserManagementController::class, 'edit'])->name('users.edit')->middleware('menu.permission:edit');
    Route::put('/update/{user}', [UserManagementController::class, 'update'])->name('users.update')->middleware('menu.permission:update');
    Route::delete('/destroy/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy')->middleware('menu.permission:destroy');
    Route::post('/reset-password/{user}', [UserManagementController::class, 'resetPassword'])->name('users.reset-password')->middleware('menu.permission:update');
});

Route::prefix('roles')->middleware('auth')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('roles.index')->middleware('menu.permission:index');
    Route::get('/create', [RoleController::class, 'create'])->name('roles.create')->middleware('menu.permission:create');
    Route::post('/store', [RoleController::class, 'store'])->name('roles.store')->middleware('menu.permission:store');
    Route::get('/edit/{role}', [RoleController::class, 'edit'])->name('roles.edit')->middleware('menu.permission:edit');
    Route::put('/update/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('menu.permission:update');
    Route::delete('/destroy/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('menu.permission:destroy');
    // Route untuk assign permission ke role
    Route::get('/{role}/menu-permissions', [RoleController::class, 'menuPermissions'])->name('roles.menu-permissions')->middleware('menu.permission:edit');
    Route::post('/{role}/menu-permissions', [RoleController::class, 'assignMenuPermissions'])->name('roles.assign-menu-permissions')->middleware('menu.permission:update');
});

Route::prefix('permissions')->middleware('auth')->group(function () {
    Route::get('/', [PermissionController::class, 'index'])->name('permissions.index')->middleware('menu.permission:index');
    Route::get('/create', [PermissionController::class, 'create'])->name('permissions.create')->middleware('menu.permission:create');
    Route::post('/store', [PermissionController::class, 'store'])->name('permissions.store')->middleware('menu.permission:store');
    Route::get('/edit/{permission}', [PermissionController::class, 'edit'])->name('permissions.edit')->middleware('menu.permission:edit');
    Route::put('/update/{permission}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('menu.permission:update');
    Route::delete('/destroy/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('menu.permission:destroy');
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::resource('approval_routes', ApprovalRouteController::class)->middleware('menu.permission:index');
});

Route::prefix('menus')->middleware('auth')->group(function () {
    Route::get('/', [MenuController::class, 'index'])->name('menus.index')->middleware('menu.permission:index');
    Route::get('/approve/{menu}', [MenuController::class, 'approve'])->name('menus.approve')->middleware('menu.permission:approve');
    Route::get('/create', [MenuController::class, 'create'])->name('menus.create')->middleware('menu.permission:create');
    Route::post('/store', [MenuController::class, 'store'])->name('menus.store')->middleware('menu.permission:store');
    Route::get('/edit/{menu}', [MenuController::class, 'edit'])->name('menus.edit')->middleware('menu.permission:edit');
    Route::put('/update/{menu}', [MenuController::class, 'update'])->name('menus.update')->middleware('menu.permission:update');
    Route::delete('/destroy/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy')->middleware('menu.permission:destroy');
});

Route::prefix('master_produksi')->middleware('auth')->group(function () {
    Route::get('/', [ProduksiController::class, 'index'])->name('master_produksi.index')->middleware('menu.permission:index');
    Route::get('/create', [ProduksiController::class, 'create'])->name('master_produksi.create')->middleware('menu.permission:create');
    Route::post('/store', [ProduksiController::class, 'store'])->name('master_produksi.store')->middleware('menu.permission:store');
    Route::get('/edit/{produksi}', [ProduksiController::class, 'edit'])->name('master_produksi.edit')->middleware('menu.permission:edit');
    Route::put('/update/{produksi}', [ProduksiController::class, 'update'])->name('master_produksi.update')->middleware('menu.permission:update');
    Route::delete('/destroy/{produksi}', [ProduksiController::class, 'destroy'])->name('master_produksi.destroy')->middleware('menu.permission:destroy');
});

Route::prefix('items')->middleware('auth')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('items.index')->middleware('menu.permission:index');
    Route::get('/show/{item}', [ItemController::class, 'show'])->name('items.show')->middleware('menu.permission:show');
    Route::get('/create', [ItemController::class, 'create'])->name('items.create')->middleware('menu.permission:create');
    Route::post('/store', [ItemController::class, 'store'])->name('items.store')->middleware('menu.permission:store');
    Route::get('/edit/{item}', [ItemController::class, 'edit'])->name('items.edit')->middleware('menu.permission:edit');
    Route::get('/checkQR/{id}', [ItemController::class, 'checkQR'])->name('items.checkQR')->middleware('menu.permission:show');
    Route::put('/update/{item}', [ItemController::class, 'update'])->name('items.update')->middleware('menu.permission:update');
    Route::post('/approve/{item}', [ItemController::class, 'approve'])->name('items.approve')->middleware('menu.permission:approve');
    Route::post('/items/{item}/revise', [ItemController::class, 'revise'])->name('items.revise')->middleware('menu.permission:approve');
    Route::post('/items/{item}/reject', [ItemController::class, 'reject'])->name('items.reject')->middleware('menu.permission:approve');
    Route::get('items/{item}/print', [ItemController::class, 'printQR'])->name('items.printQR')->middleware('menu.permission:print');
    Route::delete('/destroy/{item}', [ItemController::class, 'destroy'])->name('items.destroy')->middleware('menu.permission:destroy');
});

Route::prefix('mutasi')->middleware('auth')->group(function () {
    Route::get('/', [MutasiController::class, 'index'])->name('mutasi.index')->middleware('menu.permission:index');
    Route::get('/show/{item}', [MutasiController::class, 'show'])->name('mutasi.show')->middleware('menu.permission:show');
    Route::get('/edit/{item}', [MutasiController::class, 'edit'])->name('mutasi.edit')->middleware('menu.permission:edit');
    Route::put('/update/{item}', [MutasiController::class, 'update'])->name('mutasi.update')->middleware('menu.permission:update');
});


require __DIR__ . '/auth.php';
