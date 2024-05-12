<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengeluaran_lain;
use Illuminate\Support\Facades\Validator;
class PengeluaranLainController extends Controller
{
    public function index(){
        $pengeluaran_lain = Pengeluaran_lain::all();
        if($pengeluaran_lain->isEmpty()){
            return response([
                'message' => 'data empty',
                'data' => null
            ],404);
        }
        return response([
            'message' => 'all Pengeluaran Lain retrived',
            'data' => $pengeluaran_lain
        ],200);
    }

    public function show(string $id){
        $pengeluaran_lain = Pengeluaran_lain::find($id);
        if(!$pengeluaran_lain){
            return response([
                'message' => 'Pengeluaran Lain not found',
                'data' => null
            ],404);
        }
        return response([
            'message' => 'Pengeluaran Lain found',
            'data' => $pengeluaran_lain
        ],200);
    }

    public function store(Request $request){
        $storeData = $request->all();

        $validator = Validator::make($storeData, [
            'nama_pengeluaran' => 'required',
            'tanggal' => 'required',
            'harga' => 'required'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $pengeluaran_lain = Pengeluaran_lain::create($storeData);
        return response([
            'message' => 'success insert data',
            'data' => $pengeluaran_lain
        ],200);
    }

    public function update(Request $request, string $id_pengeluaran_lain){
        $pengeluaran_lain = Pengeluaran_lain::find($id_pengeluaran_lain);
        if(!$pengeluaran_lain){
            return response([
                'message' => 'Pengeluaran Lain not found',
                'data' => null
            ],404);
        }
        $updateData = $request->all();
        $validator = Validator::make($updateData, [
            'nama_pengeluaran' => 'required',
            'tanggal' => 'required',
            'harga' => 'required'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $pengeluaran_lain->nama_pengeluaran = $updateData['nama_pengeluaran'];
        $pengeluaran_lain->tanggal = $updateData['tanggal'];
        $pengeluaran_lain->harga = $updateData['harga'];
        $pengeluaran_lain->save();
        return response([
            'message' => 'Pengeluaran Lain updated',
            'data' => $pengeluaran_lain
        ],200);
    }

    public function destroy($id){
        $pengeluaran_lain=Pengeluaran_lain::find($id);
        if(!$pengeluaran_lain){
            return response([
                'message' => 'Pengeluaran Lain not found',
                'data' => null
            ],404);
        }
        $pengeluaran_lain->delete();
        return response([
            'message' => 'Pengeluaran Lain deleted'
        ],200);
    }
}
