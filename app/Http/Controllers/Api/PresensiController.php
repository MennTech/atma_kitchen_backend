<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\Presensi;
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
