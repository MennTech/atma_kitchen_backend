<?php

use App\Http\Controllers\Api\AuthCustomerController;
use App\Http\Controllers\Api\AuthKaryawanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResepController;
use App\Http\Controllers\Api\DetailResepController;
use App\Http\Controllers\Api\KaryawanController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\CustomerController;
use Illuminate\Support\Facades\App;

/*
|--------------------------------------------------------------------------|
|----------------------------Karyawan Route--------------------------------|
|--------------------------------------------------------------------------|
*/
Route::prefix('/karyawan')->group(function() {
    Route::post('/login', [AuthKaryawanController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/logout', [AuthKaryawanController::class, 'logout']);
        Route::post('/change-password', [AuthKaryawanController::class, 'changePassword']); 
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
Route::put('/detail-resep/{id}/{id2}',[DetailResepController::class, 'update']);
/*
|--------------------------------------------------------------------------|
|--------------------------Manager Operasional----------------------------|
|--------------------------------------------------------------------------|
*/
Route::get('/karyawan',[KaryawanController::class, 'index']);
Route::get('/karyawan/{id}',[KaryawanController::class, 'show']);
Route::post('/karyawan',[KaryawanController::class, 'store']);
Route::put('/karyawan/{id}',[KaryawanController::class, 'update']);
Route::delete('/karyawan/{id}',[KaryawanController::class, 'destroy']);
/*
|--------------------------------------------------------------------------|
|--------------------------------Owner-------------------------------------|
|--------------------------------------------------------------------------|
*/
Route::put('/role/{id}', [RoleController::class, 'update']);
Route::put('/karyawan/{id}/bonus',[KaryawanController::class, 'updateBonus']);
/*
|--------------------------------------------------------------------------|
|-------------------------------Customer-----------------------------------|
|--------------------------------------------------------------------------|
*/
Route::prefix('/customer')->group(function (){
    Route::post('/login', [AuthCustomerController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/logout', [AuthCustomerController::class, 'logout']);
        Route::get('/profile', [CustomerController::class, 'show']);
        Route::put('/profile', [CustomerController::class, 'update']);
        Route::get('/history', [CustomerController::class, 'orderHistory']);
    });
});

Route::post('/register', [AuthCustomerController::class, 'register']);