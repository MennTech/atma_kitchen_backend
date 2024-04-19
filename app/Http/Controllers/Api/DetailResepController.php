<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Detail_Resep;
use Illuminate\Support\Facades\Validator;
class DetailResepController extends Controller
{
    public function index(){
        $detail_resep = Detail_Resep::with('resep')->get();
        if($detail_resep->isEmpty()){
            return response([
                'message' => 'data empty',
                'data' => null
            ],404);
        }
        return response([
            'message' => 'all detail resep retrived',
            'data' => $detail_resep
        ],200);
    }

    public function showByIdResep(string $id){
        $detail_resep = Detail_Resep::where('id_resep',$id)->with('resep')->get();
        if($detail_resep->isEmpty()){
            return response([
                'message' => 'data empty',
                'data' => null
            ],404);
        }
        return response([
            'message' => 'detail resep found',
            'data' => $detail_resep
        ],200);
    }

    public function store (string $id_resep, Request $request){
        $storeData = $request->all();

        $validator = Validator::make($storeData, [
            'id_bahan_baku' => 'required',
            'jumlah_bahan' => 'required'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $storeData['id_resep'] = $id_resep;
        $detail_resep = Detail_Resep::create($storeData);
        return response([
            'message' => 'success insert data',
            'data' => $detail_resep
        ],200);
    }
 
    public function update(string $id_resep, string $id_bahan_baku, Request $request){
        $detail_resep = Detail_Resep::where('id_resep',$id_resep)->where('id_bahan_baku',$id_bahan_baku)->first();
        $updateData = $request->all();
        // return $detail_resep;
        $validator = Validator::make($updateData, [
            // 'id_bahan_baku' => 'required',
            'jumlah_bahan' => 'required'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        if(!$detail_resep){
            return response([
                'message' => 'data not found',
                'data' => null
            ],404);
        }
        // $detail_resep->id_bahan_baku = $updateData['id_bahan_baku'];
        $detail_resep->jumlah_bahan = $updateData['jumlah_bahan'];
        $detail_resep->save();
        return response([
            'message' => 'success update data',
            'data' => $detail_resep
        ],200);
    }
}
