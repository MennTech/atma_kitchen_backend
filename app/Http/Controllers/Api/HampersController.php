<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hampers;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HampersController extends Controller
{
    public function index()
    {
        $hampers = Hampers::with('produk')->get();
        if ($hampers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data Hampers Kosong',
            ], 404);
        }
        return response()->json([
            'status' => 'OK',
            'message' => 'Data Hampers Berhasil Ditampilkan',
            'data' => $hampers
        ]);
    }

    public function show(int $id_hampers)
    {
        $hampers = Hampers::with('produk')->find($id_hampers);
        if ($hampers == null) {
            return response()->json([
                'success' => false,
                'message' => 'Data Hampers Tidak Ditemukan',
            ], 404);
        }
        return response()->json([
            'status' => 'OK',
            'message' => 'Data Hampers Berhasil Ditampilkan',
            'data' => $hampers
        ]);
    }

    public function search(Request $request)
    {
        $key = $request->query('key');
        $hampers = Hampers::with('produk')
            ->orWhere('nama_hampers', 'like', "%{$key}%")
            ->orWhere('harga', 'like', "%{$key}%")
            ->orWhere(function ($query) use ($key) {
                $query->whereHas('produk', function ($query) use ($key) {
                    $query->where('nama_produk', 'like', "%{$key}%");
                });
            })
            ->get();

        if ($hampers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data Hampers Tidak Ditemukan',
                'data' => $hampers
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Hampers Berhasil Ditampilkan',
            'data' => $hampers
        ]);
    }

    public function store(Request $request)
    {
        $requestData = $request->all();
        $validate = Validator::make($requestData, [
            'nama_hampers' => 'required',
            'harga' => 'required|numeric|min:0',
            'id_produks' => 'array|min:2',
            'id_produks.*' => 'numeric|exists:produks,id_produk'
        ], [
            'nama_hampers.required' => 'Nama Hampers harus diisi',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh kurang dari 0',
            'id_produks.array' => 'ID Produk harus berupa array',
            'id_produks.min' => 'ID Produk minimal 2 produk',
            'id_produks.*.numeric' => 'ID Produk harus berupa angka',
            'id_produks.*.exists' => 'ID Produk tidak ditemukan'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan hampers',
                'error' => $validate->errors()
            ], 400);
        }

        $hampers = Hampers::create([
            'nama_hampers' => $request->nama_hampers,
            'harga' => $request->harga
        ]);
        if ($request->has('id_produks') && $request->id_produks != null) {
            $id_produks = $request->id_produks;
            // cek apakah produk sudah ada di hampers lain
            $produk = Produk::whereIn('id_produk', $id_produks)->get();
            foreach ($produk as $p) {
                if ($p->hampers->isNotEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Produk sudah ada di hampers lain',
                        'data' => $p
                    ], 400);
                }
                if ($p->status != 'Dijual') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Produk tidak dijual',
                        'data' => $p
                    ], 400);
                }
                if ($p->id_penitip != null) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Produk penitip tidak bisa dijadikan hampers',
                        'data' => $p
                    ], 400);
                }
            }
            /*
                placeholder id_bahan_baku = 22, karena box hampers belum ada ukurannya
                nanti ada pengecekan jenis produk, contoh:
                - Roti 1 loyang = 20x20 exclusive box
                - Roti 1/2 loyang = 20x10 exclusive box
                - Minum = botol 1L
                - dll.
            */
            $hampers->produk()->attach($id_produks, ['id_bahan_baku' => 22]);
        }


        return response()->json([
            'success' => true,
            'message' => 'Hampers berhasil ditambahkan',
            'data' => $hampers
        ]);
    }

    public function update(Request $request, int $id_hampers)
    {
        $hampers = Hampers::find($id_hampers);
        if ($hampers == null) {
            return response()->json([
                'success' => false,
                'message' => 'Hampers tidak ditemukan'
            ], 404);
        }

        $requestData = $request->all();
        $validate = Validator::make($requestData, [
            'nama_hampers' => 'required',
            'harga' => 'required|numeric|min:0',
            'id_produks' => 'array|min:2',
            'id_produks.*' => 'numeric|exists:produks,id_produk'
        ], [
            'nama_hampers.required' => 'Nama Hampers harus diisi',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh kurang dari 0',
            'id_produks.array' => 'ID Produk harus berupa array',
            'id_produks.min' => 'ID Produk minimal 2 produk',
            'id_produks.*.numeric' => 'ID Produk harus berupa angka',
            'id_produks.*.exists' => 'ID Produk tidak ditemukan'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate hampers',
                'error' => $validate->errors()
            ], 400);
        }

        $hampers->update([
            'nama_hampers' => $requestData['nama_hampers'],
            'harga' => $requestData['harga']
        ]);

        if ($request->has('id_produks') && $request->id_produks != null) {
            $id_produks = $request->id_produks;
            // cek apakah produk sudah ada di hampers lain
            $produk = Produk::whereIn('id_produk', $id_produks)->get();
            foreach ($produk as $p) {
                if ($p->hampers->isNotEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Produk sudah ada di hampers lain',
                        'data' => $p
                    ], 400);
                    if ($p->status != 'Dijual') {
                        return response()->json([
                            'success' => false,
                            'message' => 'Produk tidak dijual',
                            'data' => $p
                        ], 400);
                    }
                    if ($p->id_penitip != null) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Produk penitip tidak bisa dijadikan hampers',
                            'data' => $p
                        ], 400);
                    }
                }
            }
            /*
                placeholder id_bahan_baku = 22, karena box hampers belum ada ukurannya
                nanti ada pengecekan jenis produk, contoh:
                - Roti 1 loyang = 20x20 exclusive box
                - Roti 1/2 loyang = 20x10 exclusive box
                - Minum = botol 1L
                - dll.
            */
            $hampers->produk()->syncWithPivotValues($id_produks, ['id_bahan_baku' => 22]);
        } else {
            $hampers->produk()->detach();
        }
        return response()->json([
            'success' => true,
            'message' => 'Hampers berhasil diupdate',
            'data' => $hampers
        ]);
    }

    public function destroy(int $id_hampers)
    {
        $hampers = Hampers::find($id_hampers);
        if ($hampers == null) {
            return response()->json([
                'success' => false,
                'message' => 'Hampers tidak ditemukan'
            ], 404);
        }

        $hampers->produk()->detach();
        $hampers->delete();
        return response()->json([
            'success' => true,
            'message' => 'Hampers berhasil dihapus'
        ]);
    }
}
