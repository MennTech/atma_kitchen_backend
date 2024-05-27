<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Limit_Produk;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Integer;

class LimitProdukController extends Controller
{
    public function generateLimitProdukHariIni()
    {
        Artisan::call('app:tambah-limit-produk');
        $message = Artisan::output();
        return response()->json([
            'message' => $message
        ]);
    }

    public function show()
    {
        $date = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $date = Carbon::now()->setTimezone('Asia/Jakarta')->addDays(2)->format('Y-m-d');
        // $limitProdukDuaHari = Limit_Produk::where('tanggal', $date)->count();
        // if ($limitProdukDuaHari == 0) {
        //     $atmaProduk = Produk::where('id_resep', '!=', null)->get();
        //     foreach ($atmaProduk as $produk) {
        //         Limit_Produk::create([
        //             'id_produk' => $produk->id_produk,
        //             'tanggal' => $date,
        //             'stok' => 20
        //         ]);
        //     }
        //     $limitProduk = Limit_Produk::where('tanggal', $date)->get()->load('produk');
        //     return response([
        //         'success' => true,
        //         'message' => 'Data limit produk untuk tanggal ' . $date . ' telah diperbarui',
        //         'data' => $limitProduk
        //     ], 200);
        // }
        $atmaProduk = Produk::where('id_resep', '!=', null)->get();
        foreach ($atmaProduk as $produk) {
            $limitProduk = Limit_Produk::where('id_produk', $produk->id_produk)->where('tanggal', $date)->count();
            if ($limitProduk == 0) {
                Limit_Produk::create([
                    'id_produk' => $produk->id_produk,
                    'tanggal' => $date,
                    'stok' => 20
                ]);
            }
        }
        $limitProduk = Limit_Produk::where('tanggal', $date)->get()->load('produk');
        return response([
            'success' => true,
            'message' => 'Berhasil menampilkan data limit produk',
            'data' => $limitProduk
        ], 200);
    }

    public function showByDate(Request $request)
    {
        if ($request->query('tanggal') == null) {
            $date = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        }
        if (!Carbon::parse($request->query('tanggal'), 'Asia/Jakarta')->isValid()) {
            $date = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        }
        $date = $request->query('tanggal');
        $formattedDate = Carbon::parse($date)->format('Y-m-d');
        // $limitProduk = Limit_Produk::where('tanggal', $formattedDate)->count();
        // if($limitProduk == 0){
        //     $atmaProduk = Produk::where('id_resep', '!=', null)->get();
        //     foreach($atmaProduk as $produk){
        //         Limit_Produk::create([
        //             'id_produk' => $produk->id_produk,
        //             'tanggal' => $formattedDate,
        //             'stok' => 20
        //         ]);
        //     }
        //     $limitProduk = Limit_Produk::where('tanggal', $formattedDate)->get()->load('produk');
        //     return response([
        //         'success' => true,
        //         'message' => 'Data limit produk untuk tanggal '.$formattedDate.' telah diperbarui',
        //         'tanggal' => $formattedDate,
        //         'data' => $limitProduk
        //     ],200);
        // }
        // check if limit produk of certain product is not available
        $atmaProduk = Produk::where('id_resep', '!=', null)->get();
        foreach ($atmaProduk as $produk) {
            $limitProduk = Limit_Produk::where('id_produk', $produk->id_produk)->where('tanggal', $formattedDate)->count();
            if ($limitProduk == 0) {
                Limit_Produk::create([
                    'id_produk' => $produk->id_produk,
                    'tanggal' => $formattedDate,
                    'stok' => 20
                ]);
            }
        }
        $limitProduk = Limit_Produk::where('tanggal', $formattedDate)->get()->load('produk');
        return response([
            'success' => true,
            'message' => 'Berhasil menampilkan data limit produk',
            'data' => $limitProduk
        ], 200);
    }

    public function showByProduk(Request $request)
    {
        $id_produk = $request->query('id_produk');
        $produk = Produk::find($id_produk);
        if ($produk == null || $id_produk == null) {
            return response([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
        $limitProduk = Limit_Produk::where('id_produk', $id_produk)->get();
        return response([
            'success' => true,
            'message' => 'Berhasil menampilkan data limit produk',
            'data' => $limitProduk
        ], 200);
    }

    public function showByProdukAndDate(Request $request)
    {
        $id_produk = $request->query('id_produk');
        $produk = Produk::find($id_produk);
        if ($produk == null || $id_produk == null) {
            return response([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
        $date = $request->query('tanggal');
        if ($date == null) {
            $date = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        }
        try {
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date, 'Asia/Jakarta')->format('Y-m-d');
        } catch (\Exception $e) {
            return response([
                'success' => false,
                'message' => 'Format tanggal tidak valid'
            ], 400);
        }
        $formattedDate = Carbon::parse($date)->format('Y-m-d');
        // $limitProduk = Limit_Produk::where('id_produk', $id_produk)->where('tanggal', $formattedDate)->get();
        // if ($limitProduk->count() == 0) {
        //     $atmaProduk = Produk::where('id_resep', '!=', null)->get();
        //     foreach ($atmaProduk as $produk) {
        //         Limit_Produk::create([
        //             'id_produk' => $produk->id_produk,
        //             'tanggal' => $formattedDate,
        //             'stok' => 20
        //         ]);
        //     }
        //     $limitProduk = Limit_Produk::where('id_produk', $id_produk)->where('tanggal', $formattedDate)->get();
        // }
        $atmaProduk = Produk::where('id_resep', '!=', null)->get();
        foreach ($atmaProduk as $produk) {
            $limitProduk = Limit_Produk::where('id_produk', $produk->id_produk)->where('tanggal', $formattedDate)->count();
            if ($limitProduk == 0) {
                Limit_Produk::create([
                    'id_produk' => $produk->id_produk,
                    'tanggal' => $formattedDate,
                    'stok' => 20
                ]);
            }
        }
        return response([
            'success' => true,
            'message' => 'Berhasil menampilkan data limit produk',
            'data' => $limitProduk
        ], 200);
    }

    public function update(Request $request, int $id_limit_produk)
    {
        $limitProduk = Limit_Produk::find($id_limit_produk);
        if ($limitProduk == null) {
            return response([
                'success' => false,
                'message' => 'Data limit produk tidak ditemukan'
            ], 404);
        }
        $validate = Validator::make($request->all(), [
            'stok' => 'required|integer|min:0'
        ], [
            'stok.required' => 'Stok tidak boleh kosong',
            'stok.integer' => 'Stok harus berupa angka',
            'stok.min' => 'Stok tidak boleh kurang dari 0'
        ]);
        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => 'Gagal memperbarui data limit produk',
                'error' => $validate->errors(),
            ], 400);
        }
        $limitProduk->update($request->all());
        return response([
            'success' => true,
            'message' => 'Data limit produk berhasil diperbarui',
            'data' => $limitProduk
        ], 200);
    }
}
