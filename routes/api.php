<?php

use App\Http\Controllers\Api\AuthCustomerController;
use App\Http\Controllers\Api\AuthKaryawanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResepController;
use App\Http\Controllers\Api\DetailResepController;
use App\Http\Controllers\Api\HampersController;
use App\Http\Controllers\Api\PembelianBahanBakuController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\BahanBakuController;
use App\Http\Controllers\Api\PengeluaranLainController;
use App\Http\Controllers\Api\PenitipController;
use Illuminate\Support\Facades\App;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
/*
|--------------------------------------------------------------------------|
|----------------------------Karyawan--------------------------------|
|--------------------------------------------------------------------------|
*/
Route::prefix('/karyawan')->group(function() {
    Route::post('/login', [AuthKaryawanController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/logout', [AuthKaryawanController::class, 'logout']);
    });
});
/*
|--------------------------------------------------------------------------|
|----------------------------Administrator---------------------------------|
|--------------------------------------------------------------------------|
*/
Route::get('/resep',[ResepController::class, 'index']);
// Route::get('/resep/{id}',[ResepController::class, 'show']);
Route::get('/resep/{id}',[ResepController::class, 'show']);
Route::post('/resep',[ResepController::class, 'store']);
Route::put('/resep/{id}',[ResepController::class, 'update']);
Route::delete('/resep/{id}',[ResepController::class, 'destroy']);

Route::get('/detail-resep',[DetailResepController::class, 'index']);
Route::get('/detail-resep/{id}',[DetailResepController::class, 'showByIdResep']);
Route::post('/detail-resep/{id}',[DetailResepController::class, 'store']);
/*
|--------------------------------------------------------------------------|
|--------------------------Manager Operasional-----------------------------|
|--------------------------------------------------------------------------|
*/

/*
|--------------------------------------------------------------------------|
|--------------------------------Owner-------------------------------------|
|--------------------------------------------------------------------------|
*/

/*
|--------------------------------------------------------------------------|
|-------------------------------Customer-----------------------------------|
|--------------------------------------------------------------------------|
*/
Route::prefix('/customer')->group(function (){
    Route::post('/login', [AuthCustomerController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/logout', [AuthCustomerController::class, 'logout']);
    });
});
/*
|--------------------------------------------------------------------------|
|-------------------------------Produk-------------------------------------|
|--------------------------------------------------------------------------|
*/
Route::prefix('/produk')->group(function() {
    Route::get('/', [ProdukController::class, 'index']);
    Route::get('/atma_kitchen', [ProdukController::class, 'index_atma_kitchen']);
    Route::get('/penitip', [ProdukController::class, 'index_penitip']);

    // produk search endpoint customer, menggunakan query parameter '?keyword'
    Route::get('/cari', [ProdukController::class, 'show']);
    // produk search endpoint admin, menggunakan query parameter '?keyword'
    Route::get('/admin/cari', [ProdukController::class, 'showAdmin']);

    Route::post('/', [ProdukController::class, 'store']);
    // produk update endpoint, perlu tambahan METHOD PUT pada header request jika menggunakan form-data
    Route::post('/{id}', [ProdukController::class, 'update']); 

    // produk 'delete'
    Route::patch('/delete/{id}', [ProdukController::class, 'delete']);
});

/*
|--------------------------------------------------------------------------|
|------------------------Pembelian Bahan Baku------------------------------|
|--------------------------------------------------------------------------|
*/
Route::prefix('/pembelian-bahan-baku')->group(function(){
    Route::get('/', [PembelianBahanBakuController::class, 'index']);
    Route::get('/cari', [PembelianBahanBakuController::class, 'show']);
    Route::post('/', [PembelianBahanBakuController::class, 'store']);
    Route::put('/{id}', [PembelianBahanBakuController::class, 'update']);
    Route::delete('/{id}', [PembelianBahanBakuController::class, 'destroy']);
});
/*
|--------------------------------------------------------------------------|
|---------------------------------Hampers----------------------------------|
|--------------------------------------------------------------------------|
*/
Route::prefix('/hampers')->group(function(){
    Route::get('/', [HampersController::class, 'index']);
    Route::get('/cari', [HampersController::class, 'show']);
    Route::post('/', [HampersController::class, 'store']);
    Route::put('/{id}', [HampersController::class, 'update']);
    Route::delete('/{id}', [HampersController::class, 'destroy']);
});

Route::get('/bahan_baku',[BahanBakuController::class, 'index']);
// Route::get('/resep/{id}',[ResepController::class, 'show']);
Route::get('/bahan_baku/{id}',[BahanBakuController::class, 'show']);
Route::post('/input_bahan_baku',[BahanBakuController::class, 'store']);
Route::put('/bahan_baku_update/{id}',[BahanBakuController::class, 'update']);
Route::delete('/bahan_baku_deleted/{id}',[BahanBakuController::class, 'destroy']);

Route::get('/penitip',[PenitipController::class, 'index']);
// Route::get('/resep/{id}',[ResepController::class, 'show']);
Route::get('/penitip/{id}',[PenitipController::class, 'show']);
Route::post('/input_penitip',[PenitipController::class, 'store']);
Route::put('/penitip_update/{id}',[PenitipController::class, 'update']);
Route::delete('/penitip_deleted/{id}',[PenitipController::class, 'destroy']);

Route::get('/pengeluaran_lain',[PengeluaranLainController::class, 'index']);
// Route::get('/resep/{id}',[ResepController::class, 'show']);
Route::get('/pengeluaran_lain/{id}',[PengeluaranLainController::class, 'show']);
Route::post('/input_pengeluaran_lain',[PengeluaranLainController::class, 'store']);
Route::put('/pengeluaran_lain_update/{id}',[PengeluaranLainController::class, 'update']);
Route::delete('/pengeluaran_lain_deleted/{id}',[PengeluaranLainController::class, 'destroy']);