<?php

use App\Http\Controllers\Api\AuthCustomerController;
use App\Http\Controllers\Api\AuthKaryawanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResepController;
use App\Http\Controllers\Api\DetailResepController;
use App\Http\Controllers\Api\PembelianBahanBakuController;
use App\Http\Controllers\ProdukController;
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
    Route::patch('/{id}', [PembelianBahanBakuController::class, 'update']);
    Route::delete('/{id}', [PembelianBahanBakuController::class, 'destroy']);
});