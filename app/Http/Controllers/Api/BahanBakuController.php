<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bahan_Baku;
use Illuminate\Support\Facades\Validator;
class BahanBakuController extends Controller
{
    public function index(){
        $bahan_baku = Bahan_Baku::all();
        if($bahan_baku->isEmpty()){
            return response([
                'message' => 'data empty',
                'data' => null
            ],404);
        }
        return response([
            'message' => 'all Bahan Baku retrived',
            'data' => $bahan_baku
        ],200);
    }

    public function show(string $id){
        $bahan_baku = Bahan_Baku::find($id);
        if(!$bahan_baku){
            return response([
                'message' => 'Bahan baku not found',
                'data' => null
            ],404);
        }
        return response([
            'message' => 'Bahan Baku found',
            'data' => $bahan_baku
        ],200);
    }

    public function store(Request $request){
        $storeData = $request->all();

        $validator = Validator::make($storeData, [
            'nama_bahan_baku' => 'required',
            'stok' => 'required',
            'satuan' => 'required'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $bahan_baku = Bahan_Baku::create($storeData);
        return response([
            'message' => 'success insert data',
            'data' => $bahan_baku
        ],200);
    }

    public function update(Request $request, string $id){
        $bahan_baku = Bahan_Baku::find($id);
        if(!$bahan_baku){
            return response([
                'message' => 'Bahan Baku not found',
                'data' => null
            ],404);
        }
        $updateData = $request->all();
        $validator = Validator::make($updateData, [
            'nama_bahan_baku' => 'required',
            'stok' => 'required',
            'satuan' => 'required'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $bahan_baku->nama_bahan_baku = $updateData['nama_bahan_baku'];
        $bahan_baku->stok = $updateData['stok'];
        $bahan_baku->satuan = $updateData['satuan'];
        $bahan_baku->save();
        return response([
            'message' => 'Bahan Baku updated',
            'data' => $bahan_baku
        ],200);
    }

    public function destroy($id){
        $bahan_baku=Bahan_Baku::find($id);
        if(!$bahan_baku){
            return response([
                'message' => 'Bahan Baku not found',
                'data' => null
            ],404);
        }
        $bahan_baku->delete();
        return response([
            'message' => 'Bahan Baku deleted'
        ],200);
    }
}
