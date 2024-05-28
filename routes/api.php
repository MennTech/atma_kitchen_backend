<?php

use App\Http\Controllers\Api\AuthCustomerController;
use App\Http\Controllers\Api\AuthKaryawanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResepController;
use App\Http\Controllers\Api\KaryawanController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\HampersController;
use App\Http\Controllers\Api\PembelianBahanBakuController;
use App\Http\Controllers\Api\ProdukController;
use App\Models\Karyawan;
use App\Http\Controllers\Api\BahanBakuController;
use App\Http\Controllers\Api\PengeluaranLainController;
use App\Http\Controllers\Api\PenitipController;
use App\Http\Controllers\Api\LimitProdukController;
use App\Http\Controllers\Api\PesananController;
use Illuminate\Support\Facades\App;

Route::get('/user', function (Request $request) {
    $user = $request->user();
    if ($user == null) {
        return response()->json([
            'data' => null
        ], 404);
    }

    if ($user->id_role == null) {
        return response()->json([
            'data' => $user
        ], 200);
    }

    $user = Karyawan::with('role')->find($user->id_karyawan);
    return response()->json([
        'data' => $user
    ], 200);
})->middleware('auth:sanctum');
/*
|--------------------------------------------------------------------------|
|----------------------------Pesanan Customer------------------------------|
|--------------------------------------------------------------------------|
*/
Route::prefix('/pesanan')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/init', [PesananController::class, 'initPesanan']);
        Route::post('/PO', [PesananController::class, 'storePO']);
        Route::post('/', [PesananController::class, 'store']);
        Route::get('/keranjang', [PesananController::class, 'getKeranjangPesanan']);
        Route::patch('/keranjang', [PesananController::class, 'addDetailKeranjang']);
        Route::patch('/keranjang/metode', [PesananController::class, 'editMetodePesanan']);
        Route::patch('/keranjang/hapus', [PesananController::class, 'deleteAllProdukPesanan']);
        Route::patch('/keranjang/hapus-produk', [PesananController::class, 'deleteProdukPesanan']);
        Route::patch('/keranjang/tambah-jumlah', [PesananController::class, 'increaseQuantity']);
        Route::patch('/keranjang/kurang-jumlah', [PesananController::class, 'decreaseQuantity']);
        Route::patch('/checkout', [PesananController::class, 'checkout']);
    });
});
/*
|--------------------------------------------------------------------------|
|----------------------------Karyawan--------------------------------|
|--------------------------------------------------------------------------|
*/
Route::prefix('/karyawan')->group(function () {
    Route::post('/login', [AuthKaryawanController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthKaryawanController::class, 'logout']);
        Route::post('/change-password', [AuthKaryawanController::class, 'changePassword']);
    });
});
/*
|--------------------------------------------------------------------------|
|----------------------------Administrator---------------------------------|
|--------------------------------------------------------------------------|
*/
Route::get('/resep', [ResepController::class, 'index']);
Route::get('/resep/{id}', [ResepController::class, 'showDetail']);
Route::post('/resep', [ResepController::class, 'store']);
Route::put('/resep/{id}', [ResepController::class, 'update']);
Route::delete('/resep/{id}', [ResepController::class, 'destroyResep']);
Route::delete('/resep/{id}/detail', [ResepController::class, 'destroyAllDetail']);
Route::delete('/resep/{id}/{id2}', [ResepController::class, 'destroyDetail']);

Route::get('/bahan_baku', [BahanBakuController::class, 'index']);
Route::get('/bahan_baku/{id}', [BahanBakuController::class, 'show']);
Route::post('/input_bahan_baku', [BahanBakuController::class, 'store']);
Route::put('/bahan_baku_update/{id}', [BahanBakuController::class, 'update']);
Route::delete('/bahan_baku_deleted/{id}', [BahanBakuController::class, 'destroy']);

Route::get('/all_customer', [CustomerController::class, 'index']);
Route::get('/pesanan/{id}', [CustomerController::class, 'orderHistorybyUser']);
Route::get('/pesanan/detail/{id}', [CustomerController::class, 'detailOrderHistory']);

Route::get('/pesanan-masuk', [PesananController::class, 'showPesanan']);
// Route::get('/pesanan-customer-jarak', [PesananController::class, 'showPesananJarakNull']);
Route::put('/input-jarak-pesanan/{id}', [PesananController::class, 'updateJarakPesanan']);

// Route::get('/pesanan-customer-bayar', [PesananController::class, 'showPesananJumlahBayarNull']);
Route::put('/input-jumlah-bayar/{id}', [PesananController::class, 'updateJumlahBayarPesanan']);
/*
|--------------------------------------------------------------------------|
|--------------------------Manager Operasional-----------------------------|
|--------------------------------------------------------------------------|
*/

Route::get('/karyawan', [KaryawanController::class, 'index']);
Route::get('/karyawan/{id}', [KaryawanController::class, 'show']);
Route::post('/karyawan', [KaryawanController::class, 'store']);
Route::put('/karyawan/{id}', [KaryawanController::class, 'update']);
Route::delete('/karyawan/{id}', [KaryawanController::class, 'destroy']);

Route::get('/role',[RoleController::class, 'index']);

Route::get('/presensi', [PresensiController::class, 'generatePresensi']);
Route::get('/presensi/data', [PresensiController::class, 'show']);
Route::put('/presensi/{id}', [PresensiController::class, 'updatePresensi']);

Route::get('/penitip', [PenitipController::class, 'index']);
Route::get('/penitip/{id}', [PenitipController::class, 'show']);
Route::post('/input_penitip', [PenitipController::class, 'store']);
Route::put('/penitip_update/{id}', [PenitipController::class, 'update']);
Route::delete('/penitip_deleted/{id}', [PenitipController::class, 'destroy']);

Route::get('/pengeluaran_lain',[PengeluaranLainController::class, 'index']);
Route::get('/pengeluaran_lain/{id}',[PengeluaranLainController::class, 'show']);
Route::post('/input_pengeluaran_lain',[PengeluaranLainController::class, 'store']);
Route::put('/pengeluaran_lain_update/{id}',[PengeluaranLainController::class, 'update']);
Route::delete('/pengeluaran_lain_deleted/{id}',[PengeluaranLainController::class, 'destroy']);

Route::get('/role',[RoleController::class, 'index']);
Route::get('/role/{id}',[RoleController::class, 'show']);
Route::post('/role',[RoleController::class, 'store']);
Route::put('/role/{id}',[RoleController::class, 'update']);
Route::delete('/role/{id}',[RoleController::class, 'destroy']);
/*
|--------------------------------------------------------------------------|
|--------------------------------Owner-------------------------------------|
|--------------------------------------------------------------------------|
*/
Route::put('/role/{id}/gaji', [RoleController::class, 'updateGaji']);
Route::put('/karyawan/{id}/bonus',[KaryawanController::class, 'updateBonus']);
/*
|--------------------------------------------------------------------------|
|-------------------------------Customer-----------------------------------|
|--------------------------------------------------------------------------|
*/
Route::post('/forgot-password', [AuthCustomerController::class, 'forgotPassword'])->name('password.email');
Route::get('/tampilan', [AuthCustomerController::class, 'tampil'])->name('password.reset');
Route::post('/reset-password', [AuthCustomerController::class, 'reset']);
Route::prefix('/customer')->group(function () {
    Route::post('/login', [AuthCustomerController::class, 'login']);
    // Route untuk menangani reset password
    Route::post('/reset-password', [AuthCustomerController::class, 'reset']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthCustomerController::class, 'logout']);
        Route::get('/profile', [CustomerController::class, 'show']);
        Route::put('/profile', [CustomerController::class, 'update']);
        Route::get('/history', [CustomerController::class, 'orderHistory']);
        Route::get('/mustbepaid', [CustomerController::class, 'showOrderMustbePaid']);
        Route::post('/bukti-transfer', [CustomerController::class, 'BuktiPembayaran']);
        Route::get('/alamat', [CustomerController::class, 'getAlamatUser']);
    });
});

Route::post('/register', [AuthCustomerController::class, 'register']);

/*
|--------------------------------------------------------------------------|
|-------------------------------Produk-------------------------------------|
|--------------------------------------------------------------------------|
*/
Route::prefix('/produk')->group(function () {
    Route::get('/', [ProdukController::class, 'index']);
    Route::get('/atma_kitchen', [ProdukController::class, 'index_atma_kitchen']);
    Route::get('/penitip', [ProdukController::class, 'index_penitip']);
    Route::get('/admin/atma_kitchen', [ProdukController::class, 'index_admin_atma_kitchen']);
    Route::get('/admin/penitip', [ProdukController::class, 'index_admin_penitip']);

    // produk search endpoint customer, menggunakan query parameter '?keyword'
    Route::get('/cari', [ProdukController::class, 'search']);
    // produk search endpoint admin, menggunakan query parameter '?keyword'
    Route::get('/admin/cari', [ProdukController::class, 'searchAdmin']);
    Route::get('/{id}', [ProdukController::class, 'show']);

    Route::post('/', [ProdukController::class, 'store']);
    // produk update endpoint, perlu tambahan METHOD PUT pada header request jika menggunakan form-data
    Route::post('/{id}', [ProdukController::class, 'update']);

    // produk 'delete'
    Route::patch('/{id}', [ProdukController::class, 'delete']);
});
/*
|--------------------------------------------------------------------------|
|-----------------------------LimitProduk----------------------------------|
|--------------------------------------------------------------------------|
*/
Route::prefix('limit-produk')->group(function (){
    Route::get('/generate', [LimitProdukController::class, 'generateLimitProdukHariIni']);
    Route::get('/', [LimitProdukController::class, 'show']);
    Route::get('/cari/tanggal', [LimitProdukController::class, 'showByDate']);
    Route::get('/cari/produk', [LimitProdukController::class, 'showByProduk']);
    Route::get('/cari', [LimitProdukController::class, 'showByProdukAndDate']);
    Route::patch('/{id}', [LimitProdukController::class, 'update']);
});
/*
|--------------------------------------------------------------------------|
|------------------------Pembelian Bahan Baku------------------------------|
|--------------------------------------------------------------------------|
*/
Route::prefix('/pembelian-bahan-baku')->group(function () {
    Route::get('/', [PembelianBahanBakuController::class, 'index']);
    Route::get('/cari', [PembelianBahanBakuController::class, 'search']);
    Route::get('/{id}', [PembelianBahanBakuController::class, 'show']);
    Route::post('/', [PembelianBahanBakuController::class, 'store']);
    Route::put('/{id}', [PembelianBahanBakuController::class, 'update']);
    Route::delete('/{id}', [PembelianBahanBakuController::class, 'destroy']);
});
/*
|--------------------------------------------------------------------------|
|---------------------------------Hampers----------------------------------|
|--------------------------------------------------------------------------|
*/
Route::prefix('/hampers')->group(function () {
    Route::get('/', [HampersController::class, 'index']);
    Route::get('/cari', [HampersController::class, 'search']);
    Route::get('/{id}', [HampersController::class, 'show']);
    Route::post('/', [HampersController::class, 'store']);
    Route::post('/{id}', [HampersController::class, 'update']);
    Route::delete('/{id}', [HampersController::class, 'destroy']);
});


Route::post('/reject-pesanan/{id}', [PesananController::class, 'rejectPesanan']);
Route::post('/accept-pesanan/{id}', [PesananController::class, 'acceptPesanan']);
Route::get('/bahan-kurang', [BahanBakuController::class, 'bahanbakuKurang']);
Route::get('/pesanan-bayar-valid', [PesananController::class, 'showPesananValidPayment']);