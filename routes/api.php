<?php

use App\Http\Controllers\Api\AuthCustomerController;
use App\Http\Controllers\Api\AuthKaryawanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResepController;
use App\Http\Controllers\Api\DetailResepController;
use App\Http\Controllers\Api\KaryawanController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Support\Facades\App;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
/*
|--------------------------------------------------------------------------|
|----------------------------Karyawan Route--------------------------------|
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
Route::get('/resep/{id}',[ResepController::class, 'showDetail']);
Route::post('/resep',[ResepController::class, 'store']);
Route::put('/resep/{id}',[ResepController::class, 'update']);
Route::delete('/resep/{id}',[ResepController::class, 'destroy']);

Route::get('/detail-resep',[DetailResepController::class, 'index']);
Route::get('/detail-resep/{id}',[DetailResepController::class, 'showByIdResep']);
Route::post('/detail-resep/{id}',[DetailResepController::class, 'store']);
/*
|--------------------------------------------------------------------------|
|--------------------------Manager Operasional----------------------------|
|--------------------------------------------------------------------------|
*/

/*
|--------------------------------------------------------------------------|
|--------------------------------Owner-------------------------------------|
|--------------------------------------------------------------------------|
*/
Route::post('/role/{id}', [RoleController::class, 'update']);
Route::post('/karyawan/{id}/bonus',[KaryawanController::class, 'updateBonus']);
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