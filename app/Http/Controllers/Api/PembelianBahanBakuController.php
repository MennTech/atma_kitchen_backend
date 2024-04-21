<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bahan_Baku;
use App\Models\Pembelian_Bahan_Baku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Integer;

class PembelianBahanBakuController extends Controller
{
    public function index(Request $request){
        $pembelian_bahan_bakus = Pembelian_Bahan_Baku::all();
        if($pembelian_bahan_bakus == null){
            return response()->json([
                'success' => false,
                'message' => 'Pembelian Bahan Baku masih kosong'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menampilkan list Pembelian Bahan Baku',
            'data' => $pembelian_bahan_bakus
        ]);
    }

    public function show(Request $request){
        $key = $request->query('key');
        $pembelian_bahan_bakus = Pembelian_Bahan_Baku::with('bahanBaku')
            ->orWhere('tanggal', 'like', "%$key%")
            ->orWhere('jumlah', 'like', "%$key%")
            ->orWhere('harga', 'like', "%$key%")
            ->orWhere(function($query) use ($key){
                $query->whereHas('bahanBaku', function($query) use ($key){
                    $query->where('nama_bahan_baku', "like", "%$key%");
                });
            })
            ->get();

        if($pembelian_bahan_bakus == null){
            return response()->json([
                'success' => false,
                'message' => 'Pembelian Bahan Baku tidak ditemukan'
            ], 404);
        }

        if($pembelian_bahan_bakus->isEmpty()){
            return response()->json([
                'success' => false,
                'message' => 'Pembelian Bahan Baku tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'List Pembelian Bahan Baku',
            'data' => $pembelian_bahan_bakus
        ]);
    }

    public function store(Request $request){
        $requestData = $request->all(); 
        $validate = Validator::make($requestData, [
            'id_bahan_baku' => 'required|numeric|exists:bahan_bakus,id_bahan_baku',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0'
        ],[
            'id_bahan_baku.required' => 'ID Bahan Baku wajib diisi',
            'id_bahan_baku.numeric' => 'ID Bahan Baku harus berupa angka',
            'id_bahan_baku.exists' => 'ID Bahan Baku tidak ditemukan',
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'jumlah.required' => 'Jumlah bahan baku wajib diisi',
            'jumlah.numeric' => 'Jumlah bahan baku harus berupa angka',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 0',
            'harga.required' => 'Harga wajib diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh kurang dari 0'
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan pembelian bahan baku',
                'error' => $validate->errors()
            ], 400);
        }

        $pembelian_bahan_baku = Pembelian_Bahan_Baku::create($requestData);

        // update stok bahan baku
        $bahan_baku = Bahan_Baku::find($requestData['id_bahan_baku']);
        $bahan_baku->stok += $requestData['jumlah'];
        $bahan_baku->save();

        return response()->json([
            'success' => true,
            'message' => 'Pembelian Bahan Baku berhasil ditambahkan',
            'data' => $pembelian_bahan_baku
        ]);
    }

    public function update(Request $request, int $id_pembelian_bahan_baku){
        $pembelian_bahan_baku = Pembelian_Bahan_Baku::find($id_pembelian_bahan_baku);
        if($pembelian_bahan_baku == null){
            return response()->json([
                'success' => false,
                'message' => 'Pembelian Bahan Baku tidak ditemukan'
            ], 404);
        }

        $requestData = $request->all();
        $validate = Validator::make($requestData, [
            'id_bahan_baku' => 'required|numeric|exists:bahan_bakus,id_bahan_baku',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0'
        ],[
            'id_bahan_baku.required' => 'ID Bahan Baku wajib diisi',
            'id_bahan_baku.numeric' => 'ID Bahan Baku harus berupa angka',
            'id_bahan_baku.exists' => 'ID Bahan Baku tidak ditemukan',
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'jumlah.required' => 'Jumlah bahan baku wajib diisi',
            'jumlah.numeric' => 'Jumlah bahan baku harus berupa angka',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 0',
            'harga.required' => 'Harga wajib diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh kurang dari 0'
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pembelian bahan baku',
                'error' => $validate->errors()
            ], 400);
        }

        $stok_pembelian_lama = $pembelian_bahan_baku->jumlah;
        $pembelian_bahan_baku->update($requestData);
        // update stok bahan baku
        $bahan_baku = Bahan_Baku::find($requestData['id_bahan_baku']);
        $bahan_baku->stok += $requestData['jumlah'] - $stok_pembelian_lama;
        $bahan_baku->save();
        return response()->json([
            'success' => true,
            'message' => 'Pembelian Bahan Baku berhasil diperbarui',
            'data' => $pembelian_bahan_baku
        ]);
    }

    public function destroy(int $id_pembelian_bahan_baku){
        $pembelian_bahan_baku = Pembelian_Bahan_Baku::find($id_pembelian_bahan_baku);
        if($pembelian_bahan_baku == null){
            return response()->json([
                'success' => false,
                'message' => 'Pembelian Bahan Baku tidak ditemukan'
            ], 404);
        }
        // update stok bahan baku
        // !WARNING: bisa menyebabkan stok bahan baku negatif
        $stok_pembelian_lama = $pembelian_bahan_baku->jumlah;
        $bahan_baku = Bahan_Baku::find($pembelian_bahan_baku->id_bahan_baku);
        $bahan_baku->stok -= $stok_pembelian_lama;

        $pembelian_bahan_baku->delete();
        return response()->json([
            'success' => true,
            'message' => 'Pembelian Bahan Baku berhasil dihapus',
            'data' => $pembelian_bahan_baku
        ]);
    }
}
