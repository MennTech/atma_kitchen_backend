<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hampers;
use App\Models\Limit_Produk;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Resep;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class PesananController extends Controller
{
    public function initPesanan()
    {
        $customer = Auth::user();
        if ($customer == null) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan',
            ], 404);
        }

        $pesananKeranjang = Pesanan::where('id_customer', $customer->id_customer)->where('status', 'Keranjang')->first();
        if($pesananKeranjang != null){
            return response()->json([
                'success' => true,
                'message' => 'Pesanan keranjang sudah ada',
                'data' => $pesananKeranjang
            ], 200);
        }
        
        $pesanan = Pesanan::create([
            'id_customer' => $customer->id_customer,
            'status' => 'Keranjang'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil membuat pesanan',
            'data' => $pesanan
        ], 200);
    }

    public function getKeranjangPesanan(){
        $customer = Auth::user();
        if ($customer == null) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan',
            ], 404);
        }

        $pesanan = Pesanan::with('produkPesanan')->with('hampersPesanan')->where('id_customer', $customer->id_customer)->where('status', 'Keranjang')->first();

        if ($pesanan == null) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menampilkan pesanan',
            'data' => $pesanan
        ], 200);
    }

    public function editMetodePesanan(Request $request){
        $customer = Auth::user();
        if ($customer == null) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan',
            ], 404);
        }

        $requestData = $request->all();
        $validate = Validator::make($requestData, [
            'metode_pesan' => 'nullable',
            'id_pesanan' => 'required|exists:pesanans,id_pesanan'
        ], [
            'id_pesanan.required' => 'Id pesanan harus diisi',
            'id_pesanan.exists' => 'Pesanan tidak ditemukan'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah metode pesanan',
                'error' => $validate->errors()
            ], 400);
        }

        $pesanan = Pesanan::find($requestData['id_pesanan']);
        if ($pesanan == null) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
            ], 404);
        }

        $pesanan->update($requestData);
        
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengubah metode pesanan',
            'data' => $pesanan
        ], 200);
    }

    public function addDetailKeranjang (Request $request){
        $customer = Auth::user();
        if ($customer == null) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan',
            ], 404);
        }
        $requestData = $request->all();
        $validate = Validator::make($requestData, [
            'id_produk' => 'nullable|exists:produks,id_produk',
            'id_hampers' => 'nullable|exists:hampers,id_hampers',
            'jumlah' => 'required|numeric|min:1',
            'subtotal' => 'required|numeric|min:0',
            'id_pesanan' => 'required|exists:pesanans,id_pesanan'
        ], [
            'id_produk.exists' => 'Produk tidak ditemukan',
            'id_hampers.exists' => 'Hampers tidak ditemukan',
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah minimal 1',
            'subtotal.required' => 'Subtotal harus diisi',
            'subtotal.numeric' => 'Subtotal harus berupa angka',
            'subtotal.min' => 'Subtotal minimal 0',
            'id_pesanan.required' => 'Id pesanan harus diisi',
            'id_pesanan.exists' => 'Pesanan tidak ditemukan'
        ]);
        if($validate->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan detail pesanan',
                'error' => $validate->errors()
            ], 400);
        }

        $pesanan = Pesanan::find($requestData['id_pesanan']);
        if($pesanan == null){
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
            ], 404);
        }

        if($pesanan->status != 'Keranjang'){
            return response()->json([
                'success' => false,
                'message' => 'Pesanan sudah tidak berstatus keranjang',
            ], 400);
        }

        if($requestData['id_hampers'] == null){
            $produk = Produk::find($requestData['id_produk']);
            if($produk == null){
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan',
                ], 404);
            }
            $pesanan->produkPesanan()->attach($requestData['id_produk'], [
                'id_hampers' => $requestData['id_hampers'],
                'jumlah' => $requestData['jumlah'],
                'subtotal' => $requestData['subtotal']
            ]);
        }

        if($requestData['id_produk'] == null){
            $hampers = Hampers::find($requestData['id_hampers']);
            if($hampers == null){
                return response()->json([
                    'success' => false,
                    'message' => 'Hampers tidak ditemukan',
                ], 404);
            }
            $pesanan->hampersPesanan()->attach($requestData['id_hampers'], [
                'id_produk' => $requestData['id_produk'],
                'jumlah' => $requestData['jumlah'],
                'subtotal' => $requestData['subtotal']
            ]);
        }


        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan detail pesanan',
            'data' => $pesanan
        ], 200);
    }

    public function deleteAllProdukPesanan(Request $request){
        $customer = Auth::user();
        if ($customer == null) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan',
            ], 404);
        }
        $requestData = $request->all();
        $validate = Validator::make($requestData, [
            'id_pesanan' => 'required|exists:pesanans,id_pesanan'
        ], [
            'id_pesanan.required' => 'Id pesanan harus diisi',
            'id_pesanan.exists' => 'Pesanan tidak ditemukan'
        ]);
        if($validate->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus detail pesanan',
                'error' => $validate->errors()
            ], 400);
        }

        $pesanan = Pesanan::find($requestData['id_pesanan']);
        if($pesanan == null){
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
            ], 404);
        }

        if($pesanan->status != 'Keranjang'){
            return response()->json([
                'success' => false,
                'message' => 'Pesanan sudah tidak berstatus keranjang',
            ], 400);
        }

        $pesanan->produkPesanan()->detach();
        $pesanan->hampersPesanan()->detach();

        $pesanan->update(["metode_pesan" => null]);
        $pesanan->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus detail pesanan',
            'data' => $pesanan
        ], 200);
    }

    public function deleteProdukPesanan(Request $request){
        $customer = Auth::user();
        if ($customer == null) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan',
            ], 404);
        }
        $requestData = $request->all();
        $validate = Validator::make($requestData, [
            'id_produk' => 'nullable|exists:produks,id_produk',
            'id_hampers' => 'nullable|exists:hampers,id_hampers',
            'id_pesanan' => 'required|exists:pesanans,id_pesanan'
        ], [
            'id_produk.exists' => 'Produk tidak ditemukan',
            'id_hampers.exists' => 'Hampers tidak ditemukan',
            'id_pesanan.required' => 'Id pesanan harus diisi',
            'id_pesanan.exists' => 'Pesanan tidak ditemukan'
        ]);
        if($validate->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus detail pesanan',
                'error' => $validate->errors()
            ], 400);
        }

        $pesanan = Pesanan::find($requestData['id_pesanan']);
        if($pesanan == null){
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
            ], 404);
        }

        if($pesanan->status != 'Keranjang'){
            return response()->json([
                'success' => false,
                'message' => 'Pesanan sudah tidak berstatus keranjang',
            ], 400);
        }

        if($requestData['id_hampers'] == null){
            $produk = Produk::find($requestData['id_produk']);
            if($produk == null){
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan',
                ], 404);
            }
            $pesanan->produkPesanan()->detach($requestData['id_produk']);
        }

        if($requestData['id_produk'] == null){
            $hampers = Hampers::find($requestData['id_hampers']);
            if($hampers == null){
                return response()->json([
                    'success' => false,
                    'message' => 'Hampers tidak ditemukan',
                ], 404);
            }
            $pesanan->hampersPesanan()->detach($requestData['id_hampers']);
        }

        // check if pesanan still has detail pesanan
        $jumlahDetailPesanan = $pesanan->produkPesanan->count() + $pesanan->hampersPesanan->count();
        if($jumlahDetailPesanan == 0){
            $pesanan->update(["metode_pesan" => null]);
            $pesanan->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus salah satu detail pesanan',
            'data' => $pesanan
        ], 200);
    }

    public function increaseQuantity(Request $request) {
        $customer = Auth::user();
        if ($customer == null) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan',
            ], 404);
        }
        $requestData = $request->all();
        $validate = Validator::make($requestData, [
            'id_produk' => 'nullable|exists:produks,id_produk',
            'id_hampers' => 'nullable|exists:hampers,id_hampers',
            'id_pesanan' => 'required|exists:pesanans,id_pesanan'
        ], [
            'id_produk.exists' => 'Produk tidak ditemukan',
            'id_hampers.exists' => 'Hampers tidak ditemukan',
            'id_pesanan.required' => 'Id pesanan harus diisi',
            'id_pesanan.exists' => 'Pesanan tidak ditemukan'
        ]);
        if($validate->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan detail pesanan',
                'error' => $validate->errors()
            ], 400);
        }

        $pesanan = Pesanan::find($requestData['id_pesanan']);
        if($pesanan == null){
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
            ], 404);
        }

        if($pesanan->status != 'Keranjang'){
            return response()->json([
                'success' => false,
                'message' => 'Pesanan sudah tidak berstatus keranjang',
            ], 400);
        }

        if($requestData['id_hampers'] == null){
            $produk = Produk::find($requestData['id_produk']);
            if($produk == null){
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan',
                ], 404);
            }
            $detailPesanan = $pesanan->produkPesanan()->wherePivot('id_produk', $requestData['id_produk'])->first();
            if($detailPesanan == null){
                return response()->json([
                    'success' => false,
                    'message' => 'Detail pesanan tidak ditemukan',
                ], 404);
            }
            $pesanan->produkPesanan()->updateExistingPivot($requestData['id_produk'], [
                'jumlah' => $detailPesanan->pivot->jumlah + 1,
                'subtotal' => ($detailPesanan->pivot->jumlah + 1) * $produk->harga,
            ]);
        }

        if($requestData['id_produk'] == null){
            $hampers = Hampers::find($requestData['id_hampers']);
            if($hampers == null){
                return response()->json([
                    'success' => false,
                    'message' => 'Hampers tidak ditemukan',
                ], 404);
            }
            $detailPesanan = $pesanan->hampersPesanan()->wherePivot('id_hampers', $requestData['id_hampers'])->first();
            if($detailPesanan == null){
                return response()->json([
                    'success' => false,
                    'message' => 'Detail pesanan tidak ditemukan',
                ], 404);
            }
            $pesanan->hampersPesanan()->updateExistingPivot($requestData['id_hampers'], [
                'jumlah' => $detailPesanan->pivot->jumlah + 1,
                'subtotal' => ($detailPesanan->pivot->jumlah + 1) * $hampers->harga,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan jumlah detail pesanan',
            'data' => $pesanan
        ], 200);
    }

    public function decreaseQuantity(Request $request){
        $customer = Auth::user();
        if ($customer == null) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan',
            ], 404);
        }
        $requestData = $request->all();
        $validate = Validator::make($requestData, [
            'id_produk' => 'nullable|exists:produks,id_produk',
            'id_hampers' => 'nullable|exists:hampers,id_hampers',
            'id_pesanan' => 'required|exists:pesanans,id_pesanan'
        ], [
            'id_produk.exists' => 'Produk tidak ditemukan',
            'id_hampers.exists' => 'Hampers tidak ditemukan',
            'id_pesanan.required' => 'Id pesanan harus diisi',
            'id_pesanan.exists' => 'Pesanan tidak ditemukan'
        ]);
        if($validate->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan detail pesanan',
                'error' => $validate->errors()
            ], 400);
        }

        $pesanan = Pesanan::find($requestData['id_pesanan']);
        if($pesanan == null){
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
            ], 404);
        }

        if($pesanan->status != 'Keranjang'){
            return response()->json([
                'success' => false,
                'message' => 'Pesanan sudah tidak berstatus keranjang',
            ], 400);
        }

        if($requestData['id_hampers'] == null){
            $produk = Produk::find($requestData['id_produk']);
            if($produk == null){
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan',
                ], 404);
            }
            $detailPesanan = $pesanan->produkPesanan()->wherePivot('id_produk', $requestData['id_produk'])->first();
            if($detailPesanan == null){
                return response()->json([
                    'success' => false,
                    'message' => 'Detail pesanan tidak ditemukan',
                ], 404);
            }
            if($detailPesanan->pivot->jumlah == 1){
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah pesanan tidak bisa kurang dari 1',
                ], 400);
            }
            $pesanan->produkPesanan()->updateExistingPivot($requestData['id_produk'], [
                'jumlah' => $detailPesanan->pivot->jumlah - 1,
                'subtotal' => ($detailPesanan->pivot->jumlah - 1) * $produk->harga,
            ]);
        }

        if($requestData['id_produk'] == null){
            $hampers = Hampers::find($requestData['id_hampers']);
            if($hampers == null){
                return response()->json([
                    'success' => false,
                    'message' => 'Hampers tidak ditemukan',
                ], 404);
            }
            $detailPesanan = $pesanan->hampersPesanan()->wherePivot('id_hampers', $requestData['id_hampers'])->first();
            if($detailPesanan == null){
                return response()->json([
                    'success' => false,
                    'message' => 'Detail pesanan tidak ditemukan',
                ], 404);
            }
            if($detailPesanan->pivot->jumlah == 1){
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah pesanan tidak bisa kurang dari 1',
                ], 400);
            }
            $pesanan->hampersPesanan()->updateExistingPivot($requestData['id_hampers'], [
                'jumlah' => $detailPesanan->pivot->jumlah - 1,
                'subtotal' => ($detailPesanan->pivot->jumlah - 1) * $hampers->harga,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengurangi jumlah detail pesanan',
            'data' => $pesanan
        ], 200);
    }

    public function checkOut(Request $request){
        $customer = Auth::user();
        if ($customer == null) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan',
            ], 404);
        }
        
        $requestData = $request->all();
        $validate = Validator::make($requestData, [
            'id_pesanan' => 'required|exists:pesanans,id_pesanan',
            'tanggal_ambil' => 'required|date',
            'alamat' => 'required',
            'delivery' => 'required',
            'poin_dipakai' => 'required|numeric|min:0',
        ], [
            'id_pesanan.required' => 'Id pesanan harus diisi',
            'id_pesanan.exists' => 'Pesanan tidak ditemukan',
            'tanggal_ambil.required' => 'Tanggal ambil harus diisi',
            'tanggal_ambil.date' => 'Tanggal ambil harus berupa tanggal',
            'alamat.required' => 'Alamat harus diisi',
            'delivery.required' => 'Jenis delivery harus diisi',
            'poin_dipakai.required' => 'Poin dipakai harus diisi',
            'poin_dipakai.numeric' => 'Poin dipakai harus berupa angka',
            'poin_dipakai.min' => 'Poin dipakai tidak boleh kurang dari 0',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => $validate->errors()
            ], 400);
        }

        $pesanan = Pesanan::find($requestData['id_pesanan']);
        if($pesanan == null){
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
            ], 404);
        }

        if($pesanan->status != 'Keranjang'){
            return response()->json([
                'success' => false,
                'message' => 'Pesanan sudah tidak berstatus keranjang',
            ], 400);
        }

        $requestData['tanggal_pesan'] = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $tanggalPesan = Carbon::parse($requestData['tanggal_pesan']);
        $tanggalAmbil = Carbon::parse($requestData['tanggal_ambil']);
        if($pesanan->metode_pesan === 'PO'){
            $diffInDays = $tanggalPesan->diffInDays($tanggalAmbil);
            if($diffInDays < 2){
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat pesanan',
                    'error' => 'Tanggal ambil minimal 2 hari setelah tanggal pesan'
                ], 400);
            }
            $negatifLimit = false;
        }else if($pesanan->metode_pesan === 'Pesan Langsung'){
            $diffInHours = $tanggalPesan->diffInHours($tanggalAmbil);
            if($diffInHours < 1){
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat pesanan',
                    'error' => 'Tanggal ambil minimal 1 jam setelah tanggal pesan'
                ], 400);
            }
        }

        if($request->delivery != 'Pickup' && $request->delivery != 'Ojek Online' && $request->delivery != 'Kurir Atma Kitchen'){
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => 'Jenis delivery tidak valid'
            ], 400);
        }

        if($request->delivery == 'Pickup' || $request->delivery == 'Ojek Online'){
            $requestData['status'] = 'Menunggu Pembayaran';
        }else{
            $requestData['status'] = 'Menunggu Konfirmasi Pesanan';
        }

        $jumlahPesananProduk = $pesanan->produkPesanan->count();
        $jumlahPesananHampers = $pesanan->hampersPesanan->count();
        if($jumlahPesananProduk == 0 && $jumlahPesananHampers == 0){
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => 'Pesanan tidak boleh kosong'
            ], 400);
        }
        $total = 0;
        foreach($pesanan->produkPesanan as $produkPesanan){
            $total += $produkPesanan->pivot->subtotal;
        }
        foreach($pesanan->hampersPesanan as $hampersPesanan){
            $total += $hampersPesanan->pivot->subtotal;
        }

        $poinDipakai = $request['poin_dipakai'];
        $poinCustomer = $customer->poin;
        if($poinDipakai > $poinCustomer){
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => 'Poin tidak mencukupi'
            ], 400);
        }
        $updateCustomer = Customer::find($pesanan->id_customer);
        $updateCustomer->update([
            'poin' => $poinCustomer - $poinDipakai
        ]);
        $potonganPoin = $poinDipakai * 100;
        $total -= $potonganPoin;
        if($total < 0){
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => 'Total pesanan tidak bisa kurang dari 0'
            ], 400);
        }

        $requestData['total'] = $total;
        $poinDidapat = 0;

        while($total > 0){
            if($total % 1_000_000 != $total){
                $poinDidapat += 200;
                $total -= 1_000_000;
            }else if($total % 500_000 != $total){
                $poinDidapat += 75;
                $total -= 500_000;
            }else if($total % 100_000 != $total){
                $poinDidapat += 15;
                $total -= 100_000;
            }else if($total % 10_000 != $total){
                $poinDidapat += 1;
                $total -= 10_000;
            }else{
                break;
            }
        }
        $tanggalLahir = Carbon::parse($customer->tanggal_lahir);
        $selisihPesanDanUltah = $tanggalPesan->diffInDays($tanggalLahir);
        if (abs($selisihPesanDanUltah) <= 3) {
            $poinDidapat *= 2;
        }
        $requestData['poin_didapat'] = $poinDidapat;

        if($pesanan->metode_pesan === 'PO'){
            $limitProdukHariAmbil = Limit_Produk::where('tanggal', $tanggalAmbil->format('Y-m-d'))->first();
            if($limitProdukHariAmbil == null){
                $atmaProduk = Produk::where('id_resep', '!=', null)->get();
                foreach($atmaProduk as $produk){
                    Limit_Produk::create([
                        'id_produk' => $produk->id_produk,
                        'tanggal' => $tanggalAmbil->format('Y-m-d'),
                        'stok' => 20
                    ]);
                }
            }
            $negatifLimit = false;
            for($i = 0; $i < $jumlahPesananProduk; $i++){
                $produk = Produk::find($pesanan->produkPesanan[$i]->id_produk);
                $limitProduk = Limit_Produk::where('id_produk', $produk->id_produk)->where('tanggal', $tanggalAmbil->format('Y-m-d'))->first();
                if($limitProduk == null){
                    $limitProduk = Limit_Produk::create([
                        'id_produk' => $produk->id_produk,
                        'tanggal' => $tanggalAmbil->format('Y-m-d'),
                        'stok' => 20
                    ]);
                }
                if($limitProduk->stok - $pesanan->produkPesanan[$i]->pivot->jumlah < 0){
                    $negatifLimit = true;
                    break;
                }
            }

            for($i = 0; $i < $jumlahPesananHampers; $i++){
                $hampers = Hampers::find($pesanan->hampersPesanan[$i]->id_hampers);
                if($hampers == null){
                    return response()->json([
                        'success' => false,
                        'message' => 'Hampers tidak ditemukan',
                    ], 404);
                }
                $produkHampers = $hampers->produk;
                foreach ($produkHampers as $produk) {
                    $limitProduk = Limit_Produk::where('id_produk', $produk->id_produk)->where('tanggal', $tanggalAmbil->format('Y-m-d'))->first();
                    if($limitProduk == null){
                        $limitProduk = Limit_Produk::create([
                            'id_produk' => $produk->id_produk,
                            'tanggal' => $tanggalAmbil->format('Y-m-d'),
                            'stok' => 20
                        ]);
                    }
                    if($limitProduk->stok - $pesanan->hampersPesanan[$i]->pivot->jumlah < 0){
                        $negatifLimit = true;
                        break;
                    }
                }

            }

            if ($negatifLimit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat pesanan',
                    'error' => 'Stok produk tidak mencukupi'
                ], 400);
            }

            for($i = 0; $i < $jumlahPesananProduk; $i++){
                $produk = Produk::find($pesanan->produkPesanan[$i]->id_produk);
                $limitProduk = Limit_Produk::where('id_produk', $produk->id_produk)->where('tanggal', $tanggalAmbil->format('Y-m-d'))->first();
                $limitProduk->update([
                    'stok' => $limitProduk->stok - $pesanan->produkPesanan[$i]->pivot->jumlah
                ]);
            }
            
            for($i = 0; $i < $jumlahPesananHampers; $i++){
                $hampers = Hampers::find($pesanan->hampersPesanan[$i]->id_hampers);
                if($hampers == null){
                    return response()->json([
                        'success' => false,
                        'message' => 'Hampers tidak ditemukan',
                    ], 404);
                }
                $produkHampers = $hampers->produk;
                foreach ($produkHampers as $produk) {
                    $limitProduk = Limit_Produk::where('id_produk', $produk->id_produk)->where('tanggal', $tanggalAmbil->format('Y-m-d'))->first();
                    $limitProduk->update([
                        'stok' => $limitProduk->stok - $pesanan->hampersPesanan[$i]->pivot->jumlah
                    ]);
                }
            }
        }else if($pesanan->metode_pesan === "Pesan Langsung"){
            $negatifStok = false;
            for($i = 0; $i < $jumlahPesananProduk; $i++){
                $produk = Produk::find($pesanan->produkPesanan[$i]->id_produk);
                if($produk == null){
                    return response()->json([
                        'success' => false,
                        'message' => 'Produk tidak ditemukan',
                    ], 404);
                }
                if($produk->stok_tersedia - $pesanan->produkPesanan[$i]->pivot->jumlah < 0){
                    $negatifStok = true;
                    break;
                }
            }

            for($i = 0; $i < $jumlahPesananHampers; $i++){
                $hampers = Hampers::find($pesanan->hampersPesanan[$i]->id_hampers);
                if($hampers == null){
                    return response()->json([
                        'success' => false,
                        'message' => 'Hampers tidak ditemukan',
                    ], 404);
                }
                $produkHampers = $hampers->produk;
                foreach ($produkHampers as $produk) {
                    if($produk->stok_tersedia - $pesanan->hampersPesanan[$i]->pivot->jumlah < 0){
                        $negatifStok = true;
                        break;
                    }
                }
            }

            if ($negatifStok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat pesanan',
                    'error' => 'Stok produk tidak mencukupi'
                ], 400);
            }

            for($i = 0; $i < $jumlahPesananProduk; $i++){
                $produk = Produk::find($pesanan->produkPesanan[$i]->id_produk);
                $produk->update([
                    'stok_tersedia' => $produk->stok_tersedia - $pesanan->produkPesanan[$i]->pivot->jumlah
                ]);
            }

            for($i = 0; $i < $jumlahPesananHampers; $i++){
                $hampers = Hampers::find($pesanan->hampersPesanan[$i]->id_hampers);
                if($hampers == null){
                    return response()->json([
                        'success' => false,
                        'message' => 'Hampers tidak ditemukan',
                    ], 404);
                }
                $produkHampers = $hampers->produk;
                foreach ($produkHampers as $produk) {
                    $produk->update([
                        'stok_tersedia' => $produk->stok_tersedia - $pesanan->hampersPesanan[$i]->pivot->jumlah
                    ]);
                }
            }
        }

        $requestData['poin_didapat'] = $poinDidapat;
        
        $pesanan->update($requestData);
        $pesanan->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil membuat pesanan',
            'data' => $pesanan
        ], 200);
    }

    public function storePO(Request $request)
    {
        $customer = Auth::user();
        if ($customer == null) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan',
            ], 404);
        }

        $requestData = $request->all();
        $requestData['id_customer'] = $customer->id_customer;
        $requestData['tanggal_pesan'] = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $validate = Validator::make($requestData, [
            'tanggal_ambil' => 'required|date',
            'alamat' => 'required',
            'delivery' => 'required',
            'poin_dipakai' => 'required|numeric',
            'detail_pesanan' => 'required|array',
            'detail_pesanan.*.id_produk' => 'nullable|exists:produks,id_produk|distinct',
            'detail_pesanan.*.id_hampers' => 'nullable|exists:hampers,id_hampers|distinct',
            'detail_pesanan.*.jumlah' => 'required|numeric|min:0.5',
            'detail_pesanan.*.subtotal' => 'required|numeric|min:0',
        ], [
            'tanggal_ambil.date' => 'Tanggal ambil harus berupa tanggal',
            'alamat.required' => 'Alamat harus diisi',
            'delivery.required' => 'Jenis delivery harus diisi',
            'poin_dipakai.required' => 'Poin dipakai harus diisi',
            'detail_pesanan.required' => 'Detail pesanan harus diisi',
            'detail_pesanan.*.id_produk.exists' => 'Produk tidak ditemukan',
            'detail_pesanan.*.id_hampers.exists' => 'Hampers tidak ditemukan',
            'detail_pesanan.*.id_produk.distinct' => 'Produk tidak boleh sama',
            'detail_pesanan.*.id_hampers.distinct' => 'Hampers tidak boleh sama',
            'detail_pesanan.*.jumlah.numeric' => 'Jumlah harus berupa angka',
            'detail_pesanan.*.jumlah.min' => 'Jumlah minimal 0.5',
            'detail_pesanan.*.subtotal.numeric' => 'Subtotal harus berupa angka',
            'detail_pesanan.*.subtotal.min' => 'Subtotal minimal 0',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => $validate->errors()
            ], 400);
        }

        $tanggalPesan = Carbon::parse($requestData['tanggal_pesan']);
        $tanggalAmbil = Carbon::parse($request->tanggal_ambil);
        $diffInDays = $tanggalPesan->diffInDays($tanggalAmbil);
        if ($diffInDays < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => 'Tanggal ambil minimal 2 hari setelah tanggal pesan'
            ], 400);
        }

        if ($request->delivery != 'Pickup' && $request->delivery != 'Ojek Online' && $request->delivery != 'Kurir Atma Kitchen') {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => 'Jenis delivery tidak valid'
            ], 400);
        }

        if ($request->delivery == 'Pickup' || $request->delivery == 'Ojek Online') {
            $requestData['status'] = 'Menunggu Pembayaran';
            $requestData['jarak'] = 0;
            $requestData['ongkos_kirim'] = 0;
        } else {
            $requestData['status'] = 'Menunggu Konfirmasi Pesanan';
        }

        $jumlahPesanan = count($request->detail_pesanan);
        $total = 0;
        for ($i = 0; $i < $jumlahPesanan; $i++) {
            $total += $request->detail_pesanan[$i]['subtotal'];
        }
        
        $poinDipakai = $request['poin_dipakai'];
        $poinCustomer = $customer->poin;
        if($poinDipakai > $poinCustomer){
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => 'Poin tidak mencukupi'
            ], 400);
        }
        $potonganPoin = $poinDipakai * 100;
        $total -= $potonganPoin;
        if($total < 0){
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => 'Total pesanan tidak bisa kurang dari 0'
            ], 400);
        }
        $requestData['total'] = $total;

        $poinDidapat = 0;
        while ($total > 0){
            if($total % 1_000_000 != $total){
                $poinDidapat += 200;
                $total -= 1_000_000;
            }else if($total % 500_000 != $total){
                $poinDidapat += 75;
                $total -= 500_000;
            }else if($total % 100_000 != $total){
                $poinDidapat += 15;
                $total -= 100_000;
            }else if($total % 10_000 != $total ){
                $poinDidapat += 1;
                $total -= 10_000;
            }else{
                break;
            }
        }
        $tanggalLahir = Carbon::parse($customer->tanggal_lahir);
        $selisihPesanDanUltah = $tanggalPesan->diffInDays($tanggalLahir);
        if(abs($selisihPesanDanUltah) <= 3){
            $poinDidapat *= 2;
        }
        $requestData['poin_didapat'] = $poinDidapat;

        $limitProdukHariAmbil = Limit_Produk::where('tanggal', $tanggalAmbil->format('Y-m-d'))->first();
        if ($limitProdukHariAmbil == null) {
            $atmaProduk = Produk::where('id_resep', '!=', null)->get();
            foreach ($atmaProduk as $produk) {
                Limit_Produk::create([
                    'id_produk' => $produk->id_produk,
                    'tanggal' => $tanggalAmbil->format('Y-m-d'),
                    'stok' => 20
                ]);
            }
        }
        $negatifLimit = false;
        for ($i = 0; $i < $jumlahPesanan; $i++) {
            if ($request->detail_pesanan[$i]['id_produk'] == null) {
                $hampers = Hampers::find($request->detail_pesanan[$i]['id_hampers']);
                if ($hampers == null) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membuat pesanan',
                        'error' => 'Hampers tidak ditemukan'
                    ], 404);
                }
                // check all hampers product
                $produkHampers = $hampers->produk;
                foreach ($produkHampers as $produkHampers) {
                    $limitProduk = Limit_Produk::where('id_produk', $produkHampers->id_produk)->where('tanggal', $tanggalAmbil->format('Y-m-d'))->first();
                    if ($produkHampers == null || $limitProduk == null) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Gagal membuat pesanan',
                            'error' => 'Produk hampers tidak ditemukan'
                        ], 404);
                    }
                    // check if limit stok is enough after subtracting the order
                    if ($limitProduk->stok - $request->detail_pesanan[$i]['jumlah'] < 0) {
                        $negatifLimit = true;
                        break;
                    }
                }
            } else {
                $produk = Produk::find($request->detail_pesanan[$i]['id_produk']);
                $limitProduk = Limit_Produk::where('id_produk', $request->detail_pesanan[$i]['id_produk'])->where('tanggal', $tanggalAmbil->format('Y-m-d'))->first();
                if ($produk == null || $limitProduk == null) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membuat pesanan',
                        'error' => 'Produk tidak ditemukan'
                    ], 404);
                }
                // check if limit stok is enough after subtracting the order
                if ($limitProduk->stok - $request->detail_pesanan[$i]['jumlah'] < 0) {
                    $negatifLimit = true;
                    break;
                }
            }
        }

        if ($negatifLimit) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => 'Stok produk tidak mencukupi'
            ], 400);
        } else {
            for ($i = 0; $i < $jumlahPesanan; $i++) {
                if ($request->detail_pesanan[$i]['id_produk'] == null) {
                    $hampers = Hampers::find($request->detail_pesanan[$i]['id_hampers']);
                    $produkHampers = $hampers->produk;
                    foreach ($produkHampers as $produkHampers) {
                        $limitProduk = Limit_Produk::where('id_produk', $produkHampers->id_produk)->where('tanggal', $tanggalAmbil->format('Y-m-d'))->first();
                        $limitProduk->stok -= $request->detail_pesanan[$i]['jumlah'];
                        $limitProduk->save();
                    }
                } else {
                    $limitProduk = Limit_Produk::where('id_produk', $request->detail_pesanan[$i]['id_produk'])->where('tanggal', $tanggalAmbil->format('Y-m-d'))->first();
                    $limitProduk->stok -= $request->detail_pesanan[$i]['jumlah'];
                    $limitProduk->save();
                }
            }
        }

        $pesanan = Pesanan::create($requestData);

        for ($i = 0; $i < $jumlahPesanan; $i++) {
            if($request->detail_pesanan[$i]['id_produk'] == null){
                $pesanan->hampersPesanan()->attach($request->detail_pesanan[$i]['id_hampers'], [
                    'id_produk' => $request->detail_pesanan[$i]['id_produk'],
                    'jumlah' => $request->detail_pesanan[$i]['jumlah'],
                    'subtotal' => $request->detail_pesanan[$i]['subtotal']
                ]);
            } else {
                $pesanan->produkPesanan()->attach($request->detail_pesanan[$i]['id_produk'], [
                    'id_hampers' => $request->detail_pesanan[$i]['id_hampers'],
                    'jumlah' => $request->detail_pesanan[$i]['jumlah'],
                    'subtotal' => $request->detail_pesanan[$i]['subtotal']
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil membuat pesanan',
            'data' => $pesanan
        ], 200);
    }

    public function store(Request $request)
    {
        $customer = Auth::user();
        if ($customer == null) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan',
            ], 404);
        }

        $requestData = $request->all();
        $requestData['id_customer'] = $customer->id_customer;
        $requestData['tanggal_pesan'] = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $validate = Validator::make($requestData, [
            'tanggal_ambil' => 'required|date',
            'alamat' => 'required',
            'delivery' => 'required',
            'poin_dipakai' => 'required|numeric',
            'detail_pesanan' => 'required|array',
            'detail_pesanan.*.id_produk' => 'nullable|exists:produks,id_produk|distinct',
            'detail_pesanan.*.id_hampers' => 'nullable|exists:hampers,id_hampers|distinct',
            'detail_pesanan.*.jumlah' => 'required|numeric|min:0.5',
            'detail_pesanan.*.subtotal' => 'required|numeric|min:0',
        ], [
            'tanggal_ambil.date' => 'Tanggal ambil harus berupa tanggal',
            'alamat.required' => 'Alamat harus diisi',
            'delivery.required' => 'Jenis delivery harus diisi',
            'poin_dipakai.required' => 'Poin dipakai harus diisi',
            'detail_pesanan.required' => 'Detail pesanan harus diisi',
            'detail_pesanan.*.id_produk.exists' => 'Produk tidak ditemukan',
            'detail_pesanan.*.id_hampers.exists' => 'Hampers tidak ditemukan',
            'detail_pesanan.*.id_produk.distinct' => 'Produk tidak boleh sama',
            'detail_pesanan.*.id_hampers.distinct' => 'Hampers tidak boleh sama',
            'detail_pesanan.*.jumlah.numeric' => 'Jumlah harus berupa angka',
            'detail_pesanan.*.jumlah.min' => 'Jumlah minimal 0.5',
            'detail_pesanan.*.subtotal.numeric' => 'Subtotal harus berupa angka',
            'detail_pesanan.*.subtotal.min' => 'Subtotal minimal 0',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => $validate->errors()
            ], 400);
        }

        $tanggalPesan = Carbon::parse($requestData['tanggal_pesan']);
        $tanggalAmbil = Carbon::parse($request->tanggal_ambil);
        $diffInHours = $tanggalPesan->diffInHours($tanggalAmbil);
        if ($diffInHours < 0) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => 'Jam ambil minimal 1 jam setelah tanggal pesan'
            ], 400);
        }

        if ($request->delivery != 'Pickup' && $request->delivery != 'Ojek Online' && $request->delivery != 'Kurir Atma Kitchen') {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => 'Jenis delivery tidak valid'
            ], 400);
        }

        $requestData['jarak'] = 0;
        $requestData['ongkos_kirim'] = 0;
        $jumlahPesanan = count($request->detail_pesanan);
        $total = 0;
        for ($i = 0; $i < $jumlahPesanan; $i++) {
            $total += $request->detail_pesanan[$i]['subtotal'];
        }

        $poinDipakai = $request['poin_dipakai'];
        $poinCustomer = $customer->poin;
        if ($poinDipakai > $poinCustomer) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => 'Poin tidak mencukupi'
            ], 400);
        }
        $potonganPoin = $poinDipakai * 100;
        $total -= $potonganPoin;
        if ($total < 0) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => 'Total pesanan tidak bisa kurang dari 0'
            ], 400);
        }
        $requestData['total'] = $total;

        $poinDidapat = 0;
        while ($total > 0) {
            if ($total % 1_000_000 != $total) {
                $poinDidapat += 200;
                $total -= 1_000_000;
            }else if ($total % 500_000 != $total) {
                $poinDidapat += 75;
                $total -= 500_000;
            } else if ($total % 100_000 != $total) {
                $poinDidapat += 15;
                $total -= 100_000;
            } else if ($total % 10_000 != $total) {
                $poinDidapat += 1;
                $total -= 10_000;
            } else {
                break;
            }
        }
        $tanggalLahir = Carbon::parse($customer->tanggal_lahir);
        $selisihPesanDanUltah = $tanggalPesan->diffInDays($tanggalLahir);
        if (abs($selisihPesanDanUltah) <= 3) {
            $poinDidapat *= 2;
        }
        $requestData['poin_didapat'] = $poinDidapat;

        if ($request->delivery == 'Pickup' || $request->delivery == 'Ojek Online') {
            $requestData['status'] = 'Menunggu Pembayaran';
        } else {
            $requestData['status'] = 'Menunggu Konfirmasi Pesanan';
        }
        
        $negatifStok = false;
        for ($i = 0; $i < $jumlahPesanan; $i++) {
            if ($request->detail_pesanan[$i]['id_produk'] == null) {
                $hampers = Hampers::find($request->detail_pesanan[$i]['id_hampers']);
                if ($hampers == null) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membuat pesanan',
                        'error' => 'Hampers tidak ditemukan'
                    ], 404);
                }
                // check all hampers product
                $produkHampers = $hampers->produk;
                foreach ($produkHampers as $produkHampers) {
                    if ($produkHampers == null) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Gagal membuat pesanan',
                            'error' => 'Produk hampers tidak ditemukan'
                        ], 404);
                    }
                    if ($produkHampers[$i]->stok_tersedia - $request->detail_pesanan[$i]['jumlah'] < 0) {
                        $negatifStok = true;
                        break;
                    }
                }
            } else {
                $produk = Produk::find($request->detail_pesanan[$i]['id_produk']);
                if ($produk == null) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membuat pesanan',
                        'error' => 'Produk tidak ditemukan'
                    ], 404);
                }
                // check if limit stok is enough after subtracting the order
                if ($produk->stok_tersedia - $request->detail_pesanan[$i]['jumlah'] < 0) {
                    $negatifStok = true;
                    break;
                }
            }
        }

        if ($negatifStok) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => 'Stok produk tidak mencukupi'
            ], 400);
        } else {
            for ($i = 0; $i < $jumlahPesanan; $i++) {
                if ($request->detail_pesanan[$i]['id_produk'] == null) {
                    $hampers = Hampers::find($request->detail_pesanan[$i]['id_hampers']);
                    $produkHampers = $hampers->produk;
                    foreach ($produkHampers as $produkHampers) {
                        $produkHampers->stok_tersedia -= $request->detail_pesanan[$i]['jumlah'];
                        $produkHampers->save();
                    }
                } else {
                    $produk = Produk::find($request->detail_pesanan[$i]['id_produk']);
                    $produk->stok_tersedia -= $request->detail_pesanan[$i]['jumlah'];
                    $produk->save();
                }
            }
        }

        $pesanan = Pesanan::create($requestData);

        for ($i = 0; $i < $jumlahPesanan; $i++) {
            if($request->detail_pesanan[$i]['id_produk'] == null){
                $pesanan->hampersPesanan()->attach($request->detail_pesanan[$i]['id_hampers'], [
                    'id_produk' => $request->detail_pesanan[$i]['id_produk'],
                    'jumlah' => $request->detail_pesanan[$i]['jumlah'],
                    'subtotal' => $request->detail_pesanan[$i]['subtotal']
                ]);
            } else {
                $pesanan->produkPesanan()->attach($request->detail_pesanan[$i]['id_produk'], [
                    'id_hampers' => $request->detail_pesanan[$i]['id_hampers'],
                    'jumlah' => $request->detail_pesanan[$i]['jumlah'],
                    'subtotal' => $request->detail_pesanan[$i]['subtotal']
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil membuat pesanan',
            'data' => $pesanan
        ], 200);
    }

    public function showPesanan(){
        $pesanan = Pesanan::where('status', 'Menunggu Konfirmasi Pesanan')->orWhere('status', 'Menunggu Konfirmasi Admin')->orderBy('id_pesanan','desc')->get()->load('customer');


        if ($pesanan->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada pesanan',
                'status' => false,
                'data' => null
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil menampilkan pesanan',
            'status' => true,
            'data' => $pesanan
        ], 200);
    }

    public function updateJarakPesanan(Request $request, $id)
    {
        $pesanan = Pesanan::find($id);
        if ($pesanan == null) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'status' => false,
                'data' => null
            ], 404);
        }

        $updateJarak = $request->all();
        $validator = Validator::make($updateJarak, [
            'jarak' => 'required|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => false,
                'data' => null
            ], 400);
        }
        $pesanan->jarak = $updateJarak['jarak'];

        if ($updateJarak['jarak'] <= 5.0) {
            $pesanan->ongkos_kirim = 10000;
        } else if ($updateJarak['jarak'] >= 5.0 && $updateJarak['jarak'] <= 10.0) {
            $pesanan->ongkos_kirim = 15000;
        } else if ($updateJarak['jarak'] >= 10.0 && $updateJarak['jarak'] <= 15.0) {
            $pesanan->ongkos_kirim = 20000;
        } else {
            $pesanan->ongkos_kirim = 25000;
        }
        $pesanan->total = $pesanan->total + $pesanan->ongkos_kirim;
        $pesanan->status = 'Menunggu Pembayaran';
        $pesanan->save();

        return response()->json([
            'message' => 'Berhasil update jarak pesanan',
            'status' => true,
            'data' => $pesanan
        ], 200);
    }
    public function updateJumlahBayarPesanan(Request $request, $id)
    {
        $pesanan = Pesanan::find($id);

        if ($pesanan == null) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'status' => false,
                'data' => null
            ], 404);
        }

        $updateJumlahBayar = $request->all();
        $validator = Validator::make($updateJumlahBayar, [
            'jumlah_pembayaran' => 'required|gte:' . $pesanan->total
        ], [
            'jumlah_pembayaran.gte' => 'Jumlah pembayaran harus lebih dari atau sama dengan total harga'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => false,
                'data' => null
            ], 400);
        }

        $pesanan->jumlah_pembayaran = $updateJumlahBayar['jumlah_pembayaran'];
        $pesanan->tip = $updateJumlahBayar['jumlah_pembayaran'] - $pesanan->total;
        $pesanan->status = 'Pembayaran Valid';
        $pesanan->save();

        return response()->json([
            'message' => 'Berhasil update jumlah bayar pesanan',
            'status' => true,
            'data' => $pesanan
        ], 200);
    }
    public function rejectPesanan($id)
    {
        $pesanan = Pesanan::find($id);

        if ($pesanan == null) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'data' => null
            ], 404);
        }

        $pesanan->load('detailPesanan.produk', 'detailPesanan.hampers');
        if ($pesanan->metode_pesan == 'PO') {
            $tanggal = $pesanan->tanggal_ambil;

            if ($pesanan->detailPesanan->isEmpty()) {
                return response()->json([
                    'message' => 'Detail pesanan tidak ditemukan',
                    'data' => null
                ], 404);
            }

            foreach ($pesanan->detailPesanan as $detail) {
                if ($detail->produk) {
                    $id_produk = $detail->produk->id_produk;
                    $jumlah = $detail->jumlah;

                    $limit_produk = Limit_Produk::where('id_produk', $id_produk)
                        ->where('tanggal', $tanggal)
                        ->first();

                    if ($limit_produk) {
                        $limit_produk->stok += $jumlah;
                        $limit_produk->save();
                    }
                } else if ($detail->hampers) {
                    $hampers = $detail->hampers;
                    $hampers->load('produk');
                    foreach ($hampers->produk as $hampersProduk) {
                        $id_produk = $hampersProduk->id_produk;
                        $jumlah = $detail->jumlah;
                        $limit_produk = Limit_Produk::where('id_produk', $id_produk)
                            ->where('tanggal', $tanggal)
                            ->first();

                        if ($limit_produk) {
                            $limit_produk->stok += $jumlah;
                            $limit_produk->save();
                        }
                    }
                }
            }
            $pesanan->status = 'Pesanan Ditolak';
            $pesanan->save();
            $id_customer = $pesanan->id_customer;
            $customer = Customer::find($id_customer);
            $customer->saldo += $pesanan->jumlah_pembayaran;
            $customer->save();
            return response()->json([
                'message' => 'Pesanan berhasil ditolak dan stok diperbarui',
                'data' => $pesanan
            ], 200);
        }
        if ($pesanan->metode_pesan == 'Pesan Langsung') {
            if ($pesanan->detailPesanan->isEmpty()) {
                return response()->json([
                    'message' => 'Detail pesanan tidak ditemukan',
                    'data' => null
                ], 404);
            }
            foreach ($pesanan->detailPesanan as $detail) {
                if ($detail->produk) {
                    $id_produk = $detail->produk->id_produk;
                    $jumlah = $detail->jumlah;
                    $produk = Produk::find($id_produk);
                    $produk->stok_tersedia += $jumlah;
                    $produk->save();
                }
            }
            $pesanan->status = 'Pesanan Ditolak';
            $pesanan->save();
            $id_customer = $pesanan->id_customer;
            $customer = Customer::find($id_customer);
            $customer->saldo += $pesanan->jumlah_pembayaran;
            $customer->save();
            return response()->json([
                'message' => 'Pesanan berhasil ditolak dan stok diperbarui',
                'data' => $pesanan
            ], 200);
        }
    }
    public function acceptPesanan($id)
    {
        $pesanan = Pesanan::find($id);
        if ($pesanan == null) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'data' => null
            ], 404);
        }

        $pesanan->load('detailPesanan.produk', 'detailPesanan.hampers');

        if ($pesanan->detailPesanan->isEmpty()) {
            return response()->json([
                'message' => 'Detail pesanan tidak ditemukan',
                'data' => null
            ], 404);
        }
        foreach ($pesanan->detailPesanan as $detail) {
            if ($detail->produk) {
                $jumlah = $detail->jumlah;
                $produk = $detail->produk;
                $id_resep = $produk->id_resep;
                $resep = Resep::find($id_resep);

                if ($resep) {
                    $resep->load('detail_resep');
                    foreach ($resep->detail_resep as $detailResep) {
                        $bahanBaku = $detailResep->bahanBaku;

                        if ($bahanBaku) {
                            $bahanBaku->stok -= $detailResep->jumlah_bahan* $jumlah;
                            $bahanBaku->save();
                        }
                    }
                }
            }else if ($detail->hampers) {
                $hampers = $detail->hampers;
                $hampers->load('produk');
                foreach ($hampers->produk as $hampersProduk) {
                    $produk = $hampersProduk;
                    $id_resep = $produk->id_resep;
                    $resep = Resep::find($id_resep);
                    if ($resep) {
                        $resep->load('detail_resep');
                        foreach ($resep->detail_resep as $detailResep) {
                            $bahanBaku = $detailResep->bahanBaku;
                            if ($bahanBaku) {
                                $bahanBaku->stok -= $detailResep->jumlah_bahan;
                                $bahanBaku->save();
                            }
                        }
                    }
                }
            }
        }
        $id_customer = $pesanan->id_customer;
        $customer = Customer::find($id_customer);
        // $customer->poin += $pesanan->poin_didapat;
        $customer->update([
            'poin' => $customer->poin + $pesanan->poin_didapat
        ]);
        $customer->save();
        $pesanan->status = 'Pesanan Diterima';
        $pesanan->save();
        return response()->json([
            'message' => 'Pesanan diterima',
            'data' => $pesanan
        ], 200);
    }
    public function showPesananValidPayment(){
        $pesanan = Pesanan::where('status', 'Pembayaran Valid')->get()->load('detailPesanan.produk', 'detailPesanan.hampers', 'customer');
        if($pesanan->isEmpty()){
            return response()->json([
                'message' => 'Tidak ada Pesanan'
            ]);
        }
        return response([
            'message' => 'all Pesanan retrived',
            'data' => $pesanan
        ],200);
    }

    public function showPesananDiProses(){
        $pesanan = Pesanan::where('status', 'Diproses')->orWhere('status', 'Siap Di-pickup')->get()->load('customer');

        if($pesanan->isEmpty()){
            return response()->json([
                'message' => 'Tidak ada pesanan',
                'status' => false,
                'data' => null
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil menampilkan pesanan',
            'status' => true,
            'data' => $pesanan
        ],200);
    }

    public function updateStatusPesanan(Request $request, $id){
        $pesanan = Pesanan::find($id);

        if($pesanan == null){
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'status' => false,
                'data' => null
            ],404);
        }

        $updateStatus = $request->all();
        $validator = Validator::make($updateStatus,[
            'status' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors(),
                'status' => false,
                'data' => null
            ],400);
        }

        $pesanan->status = $updateStatus['status'];
        $pesanan->save();
    }

    public function showPesananDikirimSiapPickup(){
        $pesanan = Pesanan::where('status', 'sedang dikirim kurir')->where('status','siap di-pickup')->where('status', 'sudah di-pickup')->get();

        if($pesanan->isempty()){
            return response()->json([
                'message' => 'Pesanan tidak ada',
                'status' => false,
                'data' => null
            ],404);
        }

        return response()->json([
            'message' => 'Pesanan ditemukan',
            'status' => true,
            'data' => $pesanan
        ],200);
    }

    public function showPesananTelatBayar(){
        $pesanan = Pesanan::where('status', 'Menunggu Pembayaran')
        ->whereDate('tanggal_ambil', '<=', Carbon::now()->addDay()->setTimezone('Asia/Jakarta')->format('Y-m-d'))    
        ->get();

        if($pesanan->isEmpty()){
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'status' => false,
                'data' => null
            ],404);
        }

        return response()->json([
            'message' => 'Pesanan ditemukan',
            'status' => true,
            'data' => $pesanan
        ],200);
    }

    public function UpdateStatusBatal($id){
        $pesanan = Pesanan::find($id);

        if ($pesanan == null) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'data' => null
            ], 404);
        }

        $pesanan->load('detailPesanan.produk', 'detailPesanan.hampers');
        if ($pesanan->metode_pesan == 'PO') {
            $tanggal = $pesanan->tanggal_ambil;

            if ($pesanan->detailPesanan->isEmpty()) {
                return response()->json([
                    'message' => 'Detail pesanan tidak ditemukan',
                    'data' => null
                ], 404);
            }

            foreach ($pesanan->detailPesanan as $detail) {
                if ($detail->produk) {
                    $id_produk = $detail->produk->id_produk;
                    $jumlah = $detail->jumlah;

                    $limit_produk = Limit_Produk::where('id_produk', $id_produk)
                        ->where('tanggal', $tanggal)
                        ->first();

                    if ($limit_produk) {
                        $limit_produk->stok += $jumlah;
                        $limit_produk->save();
                    }
                } else if ($detail->hampers) {
                    $hampers = $detail->hampers;
                    $hampers->load('produk');
                    foreach ($hampers->produk as $hampersProduk) {
                        $id_produk = $hampersProduk->id_produk;
                        $jumlah = $detail->jumlah;
                        $limit_produk = Limit_Produk::where('id_produk', $id_produk)
                            ->where('tanggal', $tanggal)
                            ->first();

                        if ($limit_produk) {
                            $limit_produk->stok += $jumlah;
                            $limit_produk->save();
                        }
                    }
                }
            }
            $pesanan->status = 'Batal';
            $pesanan->save();
            return response()->json([
                'message' => 'Pesanan berhasil dibatalkan dan stok diperbarui',
                'data' => $pesanan
            ], 200);
        }
        if ($pesanan->metode_pesan == 'Pesan Langsung') {
            if ($pesanan->detailPesanan->isEmpty()) {
                return response()->json([
                    'message' => 'Detail pesanan tidak ditemukan',
                    'data' => null
                ], 404);
            }
            foreach ($pesanan->detailPesanan as $detail) {
                if ($detail->produk) {
                    $id_produk = $detail->produk->id_produk;
                    $jumlah = $detail->jumlah;
                    $produk = Produk::find($id_produk);
                    $produk->stok_tersedia += $jumlah;
                    $produk->save();
                }
            }
            $pesanan->status = 'Batal';
            $pesanan->save();
            return response()->json([
                'message' => 'Pesanan berhasil dibatalkan dan stok diperbarui',
                'data' => $pesanan
            ], 200);
        }
    }

    public function showPesananNeedsToBeProcessed(){
        $date = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $dateNextDay = Carbon::now()->addDay()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $pesanan = Pesanan::where('status', 'Pesanan Diterima')
            ->where('tanggal_ambil', $date)
            ->orWhere('tanggal_ambil', $dateNextDay)
            ->get();

        if ($pesanan->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada pesanan yang perlu diproses',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesanan yang perlu diproses ditemukan',
            'data' => $pesanan
        ], 200);
    }
}
