<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penitip;
use App\Models\Produk;
use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $produks = Produk::all();
        if ($produks == null) {
            return response()->json([
                'success' => false,
                'message' => 'Produk masih kosong'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menampilkan semua data produk',
            'produks' => $produks
        ]);
    }

    public function index_atma_kitchen(Request $request)
    {
        $produks = Produk::where('id_penitip', null)->where('status', 'Dijual')->get();
        if ($produks == null) {
            return response()->json([
                'success' => false,
                'message' => 'Produk atma kitchen masih kosong'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menampilkan data produk atma kitchen',
            'produks' => $produks
        ]);
    }

    public function index_penitip(Request $request)
    {
        $produks = Produk::where('id_penitip', '!=', null)->where('status', 'Dijual')->get();
        if ($produks == null) {
            return response()->json([
                'success' => false,
                'message' => 'Produk penitip masih kosong'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menampilkan data produk penitip',
            'produks' => $produks
        ]);
    }

    public function store(Request $request)
    {
        $requestData = request()->all();
        $validate = Validator::make($requestData, [
            'gambar_produk' => 'required|image:jpeg,png,jpg,webp|max:2048',
            'nama_produk' => 'required',
            'deskripsi_produk' => 'required',
            'harga' => 'required|numeric|min:0',
            'kategori' => 'required',
        ], [
            'gambar_produk.required' => 'Gambar produk harus diisi',
            'gambar_produk.image' => 'Gambar produk harus berupa jpeg, png, jpg, atau webp',
            'gambar_produk.max' => 'Ukuran gambar produk maksimal 2MB',
            'nama_produk.required' => 'Nama produk harus diisi',
            'deskripsi_produk.required' => 'Deskripsi produk harus diisi',
            'harga.required' => 'Harga produk harus diisi',
            'harga.numeric' => 'Harga produk harus berupa angka',
            'harga.min' => 'Harga produk tidak boleh kurang dari 0',
            'kategori.required' => 'Kategori produk harus diisi',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk',
                'error' => $validate->errors()
            ], 400);
        }

        // jika produk yg ditambah adalah produk penitip
        if ($request->has('id_penitip') && $request->id_penitip != null) {
            $penitip = Penitip::find($request->id_penitip);
            if ($penitip == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan produk penitip',
                    'error' => [
                        'id_penitip' => ['Penitip tidak ditemukan']
                    ]
                ], 404);
            }
            if ($request->has('stok_tersedia') && $request->stok_tersedia != null) {
                if ($request->stok_tersedia <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal menambahkan produk penitip',
                        'error' => [
                            'stok_tersedia' => ['Stok tersedia harus lebih dari 0']
                        ]
                    ], 400);
                }
                $requestData['id_penitip'] = $request->id_penitip;
                $requestData['stok_tersedia'] = $request->stok_tersedia;
                $owner = 'penitip';
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal Menambahkan Produk Penitip',
                    'error' => [
                        'stok_tersedia' => ['Stok tersedia harus diisi']
                    ]
                ], 400);
            }
        }
        // jika produk yg ditambah adalah produk atma kitchen
        else if ($request->has('id_resep') && $request->id_resep != null) {
            $resep = Resep::find($request->id_resep);
            if ($resep == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan produk atma kitchen',
                    'error' => [
                        'id_resep' => ['Resep tidak ditemukan']
                    ]
                ], 404);
            }
            $requestData['id_resep'] = $request->id_resep;
            $requestData['stok_tersedia'] = 0;
            $owner = 'atma kitchen';
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk',
                'error' => 'id_resep atau id_penitip harus diisi'
            ], 400);
        }

        // Upload image
        $uploadFolder = 'produk';
        $gambar_produk = $request->file('gambar_produk');
        $image_uploaded_path = $gambar_produk->store($uploadFolder, 'public');
        $uploadedImageResponse = basename($image_uploaded_path);
        $requestData['gambar_produk'] = $uploadedImageResponse;
        $requestData['status'] = 'Dijual';

        $produk = Produk::create($requestData);
        return response()->json([
            'success' => true,
            'message' => 'Produk ' . $owner . ' berhasil ditambahkan',
            'produk' => $produk
        ]);
    }

    public function update(Request $request, int $id_produk)
    {
        $produk = Produk::find($id_produk);
        if ($produk == null) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate produk',
                'error' => 'Produk tidak ditemukan'
            ], 404);
        }
        $requestData = request()->all();
        $validate = Validator::make($requestData, [
            'gambar_produk' => 'image:jpeg,png,jpg,webp|max:2048',
            'nama_produk' => 'required',
            'deskripsi_produk' => 'required',
            'harga' => 'required|numeric|min:0',
            'kategori' => 'required',
            'status' => 'required',
            'stok_tersedia' => 'required|numeric|min:0',
        ], [
            'gambar_produk.image' => 'Gambar produk harus berupa jpeg, png, jpg, atau webp',
            'gambar_produk.max' => 'Ukuran gambar produk maksimal 2MB',
            'nama_produk.required' => 'Nama produk harus diisi',
            'deskripsi_produk.required' => 'Deskripsi produk harus diisi',
            'harga.required' => 'Harga produk harus diisi',
            'harga.numeric' => 'Harga produk harus berupa angka',
            'harga.min' => 'Harga produk tidak boleh kurang dari 0',
            'kategori.required' => 'Kategori produk harus diisi',
            'status.required' => 'Status produk harus diisi',
            'stok_tersedia.required' => 'Stok tersedia harus diisi',
            'stok_tersedia.numeric' => 'Stok tersedia harus berupa angka',
            'stok_tersedia.min' => 'Stok tersedia tidak boleh kurang dari 0',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate produk',
                'error' => $validate->errors()
            ], 400);
        }

        // jika produk yg diupdate adalah produk penitip
        if ($produk->id_penitip != null) {
            if ($request->id_penitip == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate produk',
                    'error' => [
                        'id_penitip' => ['Penitip harus diisi']
                    ]
                ], 400);
            }
            $penitip = Penitip::find($request->id_penitip);
            if ($penitip == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate produk',
                    'error' => [
                        'id_penitip' => ['Penitip tidak ditemukan']
                    ]
                ], 404);
            }
            $requestData['id_penitip'] = $request->id_penitip;
        }
        // jika produk yg diupdate adalah produk atma kitchen
        else if ($produk->id_resep != null) {
            if ($request->id_resep == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate produk',
                    'error' => [
                        'id_resep' => ['Resep harus diisi']
                    ]
                ], 400);
            }
            $resep = Resep::find($request->id_resep);
            if ($resep == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate produk',
                    'error' => [
                        'id_resep' => ['Resep tidak ditemukan']
                    ]
                ], 404);
            }
            $requestData['id_resep'] = $request->id_resep;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate produk',
                'error' => 'id_resep atau id_penitip harus diisi'
            ], 400);
        }
        // jika request memiliki file gambar_produk
        if ($request->hasFile('gambar_produk')) {
            $uploadFolder = 'produk';
            $gambar_produk = $request->file('gambar_produk');
            $image_uploaded_path = $gambar_produk->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);

            // hapus gambar lama
            Storage::disk('public')->delete('produk/' . $produk->gambar_produk);

            // update gambar
            $requestData['gambar_produk'] = $uploadedImageResponse;
        }
        $produk->update($requestData);
        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diupdate',
            'produk' => $produk
        ]);
    }

    public function show(Request $request)
    {
        $keyword = $request->query('keyword');
        $produks = Produk::where(function ($query) use ($keyword) {
            $query->where('status', 'Dijual')->where('nama_produk', 'like', "%$keyword%")
                ->orWhere('deskripsi_produk', 'like', "%$keyword%")
                ->orWhere('kategori', 'like', "%$keyword%")
                ->orWhere('harga', 'like', "%$keyword%")
                ->orWhere('stok_tersedia', 'like', "%$keyword%");
        })->get();

        if ($keyword == null) {
            $produks = Produk::all();
        }
        if ($produks == null) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menampilkan data produk',
            'produk' => $produks
        ]);
    }

    public function showAdmin(Request $request)
    {
        $keyword = $request->query('keyword');
        $produks = Produk::where(function ($query) use ($keyword) {
            $query->where('nama_produk', 'like', "%$keyword%")
                ->orWhere('deskripsi_produk', 'like', "%$keyword%")
                ->orWhere('kategori', 'like', "%$keyword%")
                ->orWhere('status', 'like', "%$keyword%")
                ->orWhere('harga', 'like', "%$keyword%")
                ->orWhere('stok_tersedia', 'like', "%$keyword%")
                ->orWhere('id_penitip', 'like', "%$keyword%")
                ->orWhere('id_resep', 'like', "%$keyword%");
        })->get();

        if ($keyword == null) {
            $produks = Produk::all();
        }
        if ($produks == null) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menampilkan data produk',
            'produk' => $produks
        ]);
    }

    public function delete(Request $request, int $id_produk)
    {
        $produk = Produk::find($id_produk);
        if ($produk == null) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
        /* 
            Produk tidak dapat dihapus dari database karena memiliki foreign key
            di tabel lain yang dapat mengganggu integritas data.
            Oleh karena itu, status produk hanya diperbarui menjadi 'Tidak Dijual'.
        */
        $produk->update(['status' => 'Tidak Dijual']);
        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus',
            'produk' => $produk
        ]);
    }
}
