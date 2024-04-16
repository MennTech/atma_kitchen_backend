<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResepController;
use App\Http\Controllers\Api\DetailResepController;
use Illuminate\Support\Facades\App;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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
|--------------------------Manager Operasional----------------------------|
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