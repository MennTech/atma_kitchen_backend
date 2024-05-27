<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bahan_Baku;
use App\Models\Hampers;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'gambar_hampers' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'nama_hampers' => 'required',
            'deskripsi_hampers' => 'required',
            'harga' => 'required|numeric|min:0',
            'id_produks' => 'array|min:2',
            'id_produks.*' => 'numeric|exists:produks,id_produk',
            'id_bahan_bakus' => 'array|min:2',
            'id_bahan_bakus.*' => 'numeric|exists:bahan_bakus,id_bahan_baku'
        ], [
            'gambar_hampers.required' => 'Gambar Hampers harus diisi',
            'gambar_hampers.image' => 'Gambar Hampers harus berupa gambar',
            'gambar_hampers.mimes' => 'Gambar Hampers harus berupa file jpeg, png, jpg, webp',
            'gambar_hampers.max' => 'Gambar Hampers maksimal 2MB',
            'nama_hampers.required' => 'Nama Hampers harus diisi',
            'deskripsi_hampers.required' => 'Deskripsi Hampers harus diisi',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh kurang dari 0',
            'id_produks.array' => 'ID Produk harus berupa array',
            'id_produks.min' => 'ID Produk minimal 2 produk',
            'id_produks.*.numeric' => 'ID Produk harus berupa angka',
            'id_produks.*.exists' => 'ID Produk tidak ditemukan',
            'id_bahan_bakus.array' => 'ID Bahan Baku harus berupa array',
            'id_bahan_bakus.min' => 'ID Bahan Baku minimal 2 produk',
            'id_bahan_bakus.*.numeric' => 'ID Bahan Baku harus berupa angka',
            'id_bahan_bakus.*.exists' => 'ID Bahan Baku tidak ditemukan'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan hampers',
                'error' => $validate->errors()
            ], 400);
        }

        if ($request->has('id_produks') && $request->has('id_bahan_bakus')) {
            $id_produks = $request->id_produks;
            $id_bahan_bakus = $request->id_bahan_bakus;

            if ($request->id_produks != null) {
                // cek apakah produk sudah ada di hampers lain
                $produk = Produk::whereIn('id_produk', $id_produks)->get();
                foreach ($produk as $p) {
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

            if($request->id_bahan_bakus != null) {
                $bahan_bakus = Bahan_Baku::whereIn('id_bahan_baku', $id_bahan_bakus)->get();
                foreach($bahan_bakus as $bb){
                    $is_valid = str_contains(strtolower($bb->nama_bahan_baku), "premium") || str_contains(strtolower($bb->nama_bahan_baku), "botol") || str_contains(strtolower($bb->nama_bahan_baku), "box");
                    if(!$is_valid){
                        return response()->json([
                            'success' => false,
                            'message' => 'Bahan baku Invalid',
                            'data' => $bb
                        ], 400);
                    }
                }
            }

            if(count($id_produks) != count($id_bahan_bakus)){
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah Produk dan Bahan Baku tidak sama',
                ], 400);
            }

            $uploadFolder = 'hampers';
            $gambar_hampers = $request->file('gambar_hampers');
            $image_uploaded_path = $gambar_hampers->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            $requestData['gambar_hampers'] = $uploadedImageResponse;
    
            $hampers = Hampers::create([
                'gambar_hampers' => $requestData['gambar_hampers'],
                'nama_hampers' => $request->nama_hampers,
                'deskripsi_hampers' => $request->deskripsi_hampers,
                'harga' => $request->harga
            ]);

            for($i = 0; $i < count($id_produks); $i++){
                $hampers->produk()->attach($id_produks[$i], ['id_bahan_baku' => $id_bahan_bakus[$i]]);
            }
        }else{
            $uploadFolder = 'hampers';
            $gambar_hampers = $request->file('gambar_hampers');
            $image_uploaded_path = $gambar_hampers->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            $requestData['gambar_hampers'] = $uploadedImageResponse;
    
            $hampers = Hampers::create([
                'gambar_hampers' => $requestData['gambar_hampers'],
                'nama_hampers' => $request->nama_hampers,
                'deskripsi_hampers' => $request->deskripsi_hampers,
                'harga' => $request->harga
            ]);
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
            'gambar_hampers' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'nama_hampers' => 'required',
            'deskripsi_hampers' => 'required',
            'harga' => 'required|numeric|min:0',
            'id_produks' => 'array|min:2',
            'id_produks.*' => 'numeric|exists:produks,id_produk',
            'id_bahan_bakus' => 'array|min:2',
            'id_bahan_bakus.*' => 'numeric|exists:bahan_bakus,id_bahan_baku'
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

        if ($request->has('id_produks') && $request->has('id_bahan_bakus')) {
            $id_produks = $request->id_produks;
            $id_bahan_bakus = $request->id_bahan_bakus;
            // cek apakah produk sudah ada di hampers lain
            if ($request->id_produks != null) {
                // cek apakah produk sudah ada di hampers lain
                $produk = Produk::whereIn('id_produk', $id_produks)->get();
                foreach ($produk as $p) {
                    if ($hampers->produk->contains($p)) {
                        continue;
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
            }

            if ($request->id_bahan_bakus != null) {
                $bahan_bakus = Bahan_Baku::whereIn('id_bahan_baku', $id_bahan_bakus)->get();
                foreach ($bahan_bakus as $bb) {
                    $is_valid = str_contains(strtolower($bb->nama_bahan_baku), "premium") || str_contains(strtolower($bb->nama_bahan_baku), "botol") || str_contains(strtolower($bb->nama_bahan_baku), "box");
                    if (!$is_valid) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Bahan baku Invalid',
                            'data' => $bb
                        ], 400);
                    }
                }
            }

            if (count($id_produks) != count($id_bahan_bakus)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah Produk dan Bahan Baku tidak sama',
                ], 400);
            }

            if ($request->hasFile('gambar_hampers')) {
                $uploadFolder = 'hampers';
                $gambar_hampers = $request->file('gambar_hampers');
                $image_uploaded_path = $gambar_hampers->store($uploadFolder, 'public');
                $uploadedImageResponse = basename($image_uploaded_path);
                $requestData['gambar_hampers'] = $uploadedImageResponse;

                Storage::disk('public')->delete('hampers/' . $hampers->gambar_hampers);

                $hampers->update([
                    'gambar_hampers' => $requestData['gambar_hampers'],
                    'nama_hampers' => $requestData['nama_hampers'],
                    'deskripsi_hampers' => $requestData['deskripsi_hampers'],
                    'harga' => $requestData['harga']
                ]);
            } else {
                $hampers->update([
                    'nama_hampers' => $requestData['nama_hampers'],
                    'deskripsi_hampers' => $requestData['deskripsi_hampers'],
                    'harga' => $requestData['harga']
                ]);
            }

            $hampers->produk()->detach();
            for($i = 0; $i < count($id_produks); $i++){
                $hampers->produk()->attach($id_produks[$i], ['id_bahan_baku' => $id_bahan_bakus[$i]]);
            }
        } else {
            if ($request->hasFile('gambar_hampers')) {
                $uploadFolder = 'hampers';
                $gambar_hampers = $request->file('gambar_hampers');
                $image_uploaded_path = $gambar_hampers->store($uploadFolder, 'public');
                $uploadedImageResponse = basename($image_uploaded_path);
                $requestData['gambar_hampers'] = $uploadedImageResponse;

                Storage::disk('public')->delete('hampers/' . $hampers->gambar_hampers);

                $hampers->update([
                    'gambar_hampers' => $requestData['gambar_hampers'],
                    'nama_hampers' => $requestData['nama_hampers'],
                    'deskripsi_hampers' => $requestData['deskripsi_hampers'],
                    'harga' => $requestData['harga']
                ]);
            } else {
                $hampers->update([
                    'nama_hampers' => $requestData['nama_hampers'],
                    'deskripsi_hampers' => $requestData['deskripsi_hampers'],
                    'harga' => $requestData['harga']
                ]);
            }
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
