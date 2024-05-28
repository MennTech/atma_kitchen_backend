<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bahan_Baku;
use Illuminate\Support\Facades\Validator;
use Psy\Readline\Hoa\Console;

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
            'stok' => 'required|numeric|min:1',
            'satuan' => 'required'
        ], [
            'stok.required' => 'Stok harus diisi.',
            'stok.numeric' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok harus lebih besar dari 0.',
            'nama_bahan_baku.required' => 'Nama bahan baku harus diisi.',
            'satuan.required' => 'Satuan harus diisi.'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $bahan_baku = Bahan_Baku::create($storeData);
        return response([
            'message' => 'success Menambahkan Bahan Baku baru',
            'data' => $bahan_baku
        ],200);
    }

    public function update(Request $request, string $id_bahan_baku){
        $bahan_baku = Bahan_Baku::find($id_bahan_baku);
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
    public function bahanbakuKurang(){
        $bahan_baku = Bahan_Baku::where('stok', '<', 0)->get();
        if($bahan_baku->isEmpty()){
            return response([
                'message' => 'Bahan Baku tidak kurang',
                'data' => null
            ],404);
        }
        return response([
            'message' => 'Bahan Baku kurang',
            'data' => $bahan_baku
        ],200);
    }
}
