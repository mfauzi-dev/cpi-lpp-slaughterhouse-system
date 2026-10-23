<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataHasilProduksiController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MetalDetectorController;
use App\Http\Controllers\ProductionBatchController;
use App\Http\Controllers\ProductivityController;
use App\Http\Controllers\RekapitulasiProduksiController;
use App\Http\Controllers\UserController;
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

Route::get('/', [AuthController::class, 'createLogin'])->name('login');
Route::post('/store', [AuthController::class, 'storeLogin'])->name('login.store');

Route::middleware(['auth'])->group(function() {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('password/edit', [AuthController::class, 'edit'])->name('password.edit');
    Route::put('password/update', [AuthController::class, 'update'])->name('password.update');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/manager/export', [DashboardController::class, 'exportManager'])->name('dashboard.manager.export');
    Route::get('/production-batch/by-date/{date}', [ProductionBatchController::class, 'getByDate'])->name('production-batch.by-date');

});

Route::prefix('operator')->middleware(['auth', 'role:Operator'])->group(function() {
    Route::prefix('material')->group(function () {
        Route::get('/', [MaterialController::class, 'index'])->name('operator.material.index');
        Route::get('/create', [MaterialController::class, 'create'])->name('operator.material.create');
        Route::post('/store', [MaterialController::class, 'store'])->name('operator.material.store');
        Route::get('/{id}/edit', [MaterialController::class, 'edit'])->name('operator.material.edit');
        Route::put('/{id}/update', [MaterialController::class, 'update'])->name('operator.material.update');
        Route::delete('/{id}/delete', [MaterialController::class, 'destroy'])->name('operator.material.destroy');
        Route::get('/import', [MaterialController::class, 'importPage'])->name('operator.material.import');
        Route::post('/import', [MaterialController::class, 'upload'])->name('operator.material.upload');
    });

    Route::prefix('production-batch')->group(function () {
        Route::get('/', [ProductionBatchController::class, 'index'])->name('operator.production-batch.index');
        Route::get('/create', [ProductionBatchController::class, 'create'])->name('operator.production-batch.create');
        Route::post('/store', [ProductionBatchController::class, 'store'])->name('operator.production-batch.store');

        Route::get('/by-date/{date}', [ProductionBatchController::class, 'getByDate'])->name('operator.production-batch.by-date');

        Route::get('/{id}', [ProductionBatchController::class, 'detail'])->name('operator.production-batch.detail');
        Route::get('/{id}/edit', [ProductionBatchController::class, 'edit'])->name('operator.production-batch.edit');
        Route::put('/{id}/update', [ProductionBatchController::class, 'update'])->name('operator.production-batch.update');
        Route::delete('/{id}/delete', [ProductionBatchController::class, 'destroy'])->name('operator.production-batch.destroy');

        Route::get('/{id}/export', [ProductionBatchController::class, 'export'])->name('operator.production-batch.export');
    });

    Route::prefix('bahan-baku')->group(function () {
        Route::get('/', [BahanBakuController::class, 'index'])->name('operator.bahan-baku.index');
        Route::get('/create', [BahanBakuController::class, 'create'])->name('operator.bahan-baku.create');
        Route::post('/store', [BahanBakuController::class, 'store'])->name('operator.bahan-baku.store');

        Route::get('/{id}', [BahanBakuController::class, 'detail'])->name('operator.bahan-baku.detail');
        Route::get('/{id}/edit', [BahanBakuController::class, 'edit'])->name('operator.bahan-baku.edit');
        Route::put('/{id}/update', [BahanBakuController::class, 'update'])->name('operator.bahan-baku.update');
        Route::delete('/{id}/delete', [BahanBakuController::class, 'destroy'])->name('operator.bahan-baku.destroy');

        Route::post('/bulk-destroy', [BahanBakuController::class, 'bulkDestroy'])->name('operator.bahan-baku.bulk-destroy');
    });

    Route::prefix('hasil-produksi')->group(function () {
        Route::get('/', [DataHasilProduksiController::class, 'index'])->name('operator.hasil-produksi.index');
        Route::get('/create', [DataHasilProduksiController::class, 'create'])->name('operator.hasil-produksi.create');
        Route::post('/store', [DataHasilProduksiController::class, 'store'])->name('operator.hasil-produksi.store');

        Route::get('/get-products', [DataHasilProduksiController::class, 'getProducts'])->name('operator.hasil-produksi.get-products');
        Route::get('/get-po-list', [DataHasilProduksiController::class, 'getPoList'])->name('operator.hasil-produksi.get-po-list');
        Route::get('/get-bahan-baku-by-po', [DataHasilProduksiController::class, 'getBahanBakuByPo'])->name('operator.hasil-produksi.get-bahan-baku-by-po');

        Route::get('/{id}', [DataHasilProduksiController::class, 'detail'])->name('operator.hasil-produksi.detail');
        Route::get('/{id}/edit', [DataHasilProduksiController::class, 'edit'])->name('operator.hasil-produksi.edit');
        Route::put('/{id}/update', [DataHasilProduksiController::class, 'update'])->name('operator.hasil-produksi.update');
        Route::delete('/{id}/delete', [DataHasilProduksiController::class, 'destroy'])->name('operator.hasil-produksi.destroy');

        Route::post('/bulk-destroy', [DataHasilProduksiController::class, 'bulkDestroy'])->name('operator.hasil-produksi.bulk-destroy');
    });

    Route::prefix('rekapitulasi-produksi')->group(function () {
        Route::get('/', [RekapitulasiProduksiController::class, 'index'])->name('operator.rekapitulasi-produksi.index');
        Route::get('/create', [RekapitulasiProduksiController::class, 'create'])->name('operator.rekapitulasi-produksi.create');
        Route::post('/store', [RekapitulasiProduksiController::class, 'store'])->name('operator.rekapitulasi-produksi.store');

        Route::get('/get-po-list', [RekapitulasiProduksiController::class, 'getPoList'])->name('operator.rekapitulasi-produksi.get-po-list');
        Route::get('/get-bahan-baku-by-po', [RekapitulasiProduksiController::class, 'getBahanBakuByPo'])->name('operator.rekapitulasi-produksi.get-bahan-baku-by-po');

        Route::get('/{id}/edit', [RekapitulasiProduksiController::class, 'edit'])->name('operator.rekapitulasi-produksi.edit');
        Route::put('/{id}/update', [RekapitulasiProduksiController::class, 'update'])->name('operator.rekapitulasi-produksi.update');
        Route::delete('/{id}/delete', [RekapitulasiProduksiController::class, 'destroy'])->name('operator.rekapitulasi-produksi.destroy');

        Route::post('/bulk-destroy', [RekapitulasiProduksiController::class, 'bulkDestroy'])->name('operator.rekapitulasi-produksi.bulk-destroy');
        Route::get('/{id}', [RekapitulasiProduksiController::class, 'detail'])->name('operator.rekapitulasi-produksi.detail');
    });

    Route::prefix('productivity')->group(function () {
        Route::get('/', [ProductivityController::class, 'index'])->name('operator.productivity.index');
        Route::get('/create', [ProductivityController::class, 'create'])->name('operator.productivity.create');
        Route::post('/store', [ProductivityController::class, 'store'])->name('operator.productivity.store');

        Route::get('/get-po-list', [ProductivityController::class, 'getPoList'])->name('operator.productivity.get-po-list');
        Route::get('/get-bahan-baku-by-po', [ProductivityController::class, 'getBahanBakuByPo'])->name('operator.productivity.get-bahan-baku-by-po');


        Route::post('/bulk-destroy', [ProductivityController::class, 'bulkDestroy'])->name('operator.productivity.bulk-destroy');

        Route::get('/{id}/edit', [ProductivityController::class, 'edit'])->name('operator.productivity.edit');
        Route::put('/{id}/update', [ProductivityController::class, 'update'])->name('operator.productivity.update');
        Route::delete('/{id}/delete', [ProductivityController::class, 'destroy'])->name('operator.productivity.destroy');

        Route::get('/{id}', [ProductivityController::class, 'detail'])->name('operator.productivity.detail');
});

    Route::prefix('metal-detector')->group(function () {
        Route::get('/', [MetalDetectorController::class, 'index'])->name('operator.metal-detector.index');
        Route::get('/create', [MetalDetectorController::class, 'create'])->name('operator.metal-detector.create');
        Route::post('/store', [MetalDetectorController::class, 'store'])->name('operator.metal-detector.store');

        Route::post('/bulk-destroy', [MetalDetectorController::class, 'bulkDestroy'])->name('operator.metal-detector.bulk-destroy');

        Route::get('/{id}/edit', [MetalDetectorController::class, 'edit'])->name('operator.metal-detector.edit');
        Route::put('/{id}/update', [MetalDetectorController::class, 'update'])->name('operator.metal-detector.update');
        Route::delete('/{id}/delete', [MetalDetectorController::class, 'destroy'])->name('operator.metal-detector.destroy');

        Route::get('/{id}', [MetalDetectorController::class, 'detail'])->name('operator.metal-detector.detail');
    });
});

Route::prefix('manager')->middleware(['auth', 'role:Manager'])->group(function () {
    Route::prefix('production-batch')->group(function () {
        Route::get('/', [ProductionBatchController::class, 'managerIndex'])->name('manager.production-batch.index');
        Route::get('/{id}', [ProductionBatchController::class, 'managerDetail'])->name('manager.production-batch.detail');
        Route::get('/{id}/export', [ProductionBatchController::class, 'managerExport'])->name('manager.production-batch.export');
    });

    Route::prefix('bahan-baku')->group(function () {
        Route::get('/', [BahanBakuController::class, 'managerIndex'])->name('manager.bahan-baku.index');
        Route::get('/{id}', [BahanBakuController::class, 'managerDetail'])->name('manager.bahan-baku.detail');
    });

    Route::prefix('hasil-produksi')->group(function () {
        Route::get('/', [DataHasilProduksiController::class, 'managerIndex'])->name('manager.hasil-produksi.index');
        Route::get('/{id}', [DataHasilProduksiController::class, 'managerDetail'])->name('manager.hasil-produksi.detail');
    });

    Route::prefix('rekapitulasi-produksi')->group(function () {
        Route::get('/', [RekapitulasiProduksiController::class, 'managerIndex'])->name('manager.rekapitulasi-produksi.index');
        Route::get('/{id}', [RekapitulasiProduksiController::class, 'managerDetail'])->name('manager.rekapitulasi-produksi.detail');
    });

    Route::prefix('productivity')->group(function () {
        Route::get('/', [ProductivityController::class, 'managerIndex'])->name('manager.productivity.index');
        Route::get('/{id}', [ProductivityController::class, 'managerDetail'])->name('manager.productivity.detail');
    });

    Route::prefix('metal-detector')->group(function () {
        Route::get('/', [MetalDetectorController::class, 'managerIndex'])->name('manager.metal-detector.index');
        Route::get('/{id}', [MetalDetectorController::class, 'managerDetail'])->name('manager.metal-detector.detail');
    });

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('manager.user.index');
        Route::get('/create', [UserController::class, 'create'])->name('manager.user.create');
        Route::post('/store', [UserController::class, 'store'])->name('manager.user.store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('manager.user.edit');
        Route::put('/{id}/update', [UserController::class, 'update'])->name('manager.user.update');
        Route::delete('/{id}/delete', [UserController::class, 'destroy'])->name('manager.user.destroy');
    });
});
