<?php

use App\Http\Controllers\ApprovalRouteController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
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
    Route::get('/approve/{id}', [MenuController::class, 'approve'])->name('menus.approve')->middleware('menu.permission:approve');
    Route::get('/create', [MenuController::class, 'create'])->name('menus.create')->middleware('menu.permission:create');
    Route::post('/store', [MenuController::class, 'store'])->name('menus.store')->middleware('menu.permission:store');
    Route::get('/edit/{id}', [MenuController::class, 'edit'])->name('menus.edit')->middleware('menu.permission:edit');
    Route::put('/update/{id}', [MenuController::class, 'update'])->name('menus.update')->middleware('menu.permission:update');
    Route::delete('/destroy/{id}', [MenuController::class, 'destroy'])->name('menus.destroy')->middleware('menu.permission:destroy');
});

Route::prefix('items')->middleware('auth')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('items.index')->middleware('menu.permission:index');
    Route::get('/show/{id}', [ItemController::class, 'show'])->name('items.show')->middleware('menu.permission:show');
    Route::post('/approve/{id}', [ItemController::class, 'approve'])->name('items.approve')->middleware('menu.permission:approve');
    Route::get('/create', [ItemController::class, 'create'])->name('items.create')->middleware('menu.permission:create');
    Route::post('/store', [ItemController::class, 'store'])->name('items.store')->middleware('menu.permission:store');
    Route::get('/edit/{id}', [ItemController::class, 'edit'])->name('items.edit')->middleware('menu.permission:edit');
    Route::put('/update/{id}', [ItemController::class, 'update'])->name('items.update')->middleware('menu.permission:update');
    Route::delete('/destroy/{id}', [ItemController::class, 'destroy'])->name('items.destroy')->middleware('menu.permission:destroy');
});


require __DIR__ . '/auth.php';
