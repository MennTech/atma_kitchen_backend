<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PresensiController extends Controller
{
    public function generatePresensi()
    {
        Artisan::call('tambah:presensi');
        $message = Artisan::output();
        return response()->json([
            'message' => $message
        ]);
    }

    public function show(){
        $date = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $presensi = Presensi::where('tanggal', $date)->get()->load('karyawan');
        return response([
            'message' => 'Berhasil menampilkan data presensi',
            'data' => $presensi
        ],200);
    }

    public function updatePresensi(Request $request, string $id){
        $presensi = Presensi::find($id);
        $storeData = $request->all();
        $validator = Validator::make($storeData, [
            'status' => 'required'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $presensi->status = $storeData['status'];
        $presensi->save();
        return response([
            'message' => 'berhasil update status presensi',
            'data' => $presensi
        ],200);
    }
}
