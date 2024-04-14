<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Resep;
use App\Models\Detail_Resep;
class ResepController extends Controller
{
    public function index(){
        $resep = Resep::all();
        if($resep->isEmpty()){
            return response([
                'message' => 'data empty',
                'data' => null
            ],404);
        }
        return response([
            'message' => 'all resep retrived',
            'data' => $resep
        ],200);
    }

    public function show(string $id)
    {
        $resep = Resep::find($id);
        if(!$resep){
            return response([
                'message' => 'resep not found',
                'data' => null
            ],404);
        }
        $detail_resep = Detail_Resep::where('id_resep',$id)->get();
        return response([
            'message' => 'resep found',
            'data' => $resep,
            'detail_resep' => $detail_resep
        ],200);
    }

    public function store(Request $request){
        $storeData = $request->all();

        $validator = Validator::make($storeData, [
            'nama_resep' => 'required'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $resep = Resep::create($storeData);
        return response([
            'message' => 'success insert data',
            'data' => $resep
        ],200);
    }

    public function update(Request $request, string $id){
        $resep = Resep::find($id);
        if(!$resep){
            return response([
                'message' => 'resep not found',
                'data' => null
            ],404);
        }
        $updateData = $request->all();

        $validator = Validator::make($updateData, [
            'nama_resep' => 'required'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $resep->nama_resep = $updateData['nama_resep'];
        $resep->save();
        return response([
            'message' => 'resep updated',
            'data' => $resep
        ],200);

    }

    public function destroy($id){
        $resep=Resep::find($id);
        if(!$resep){
            return response([
                'message' => 'resep not found',
                'data' => null
            ],404);
        }

        if($resep->delete()){
            return response([
                'message' => 'resep deleted',
                'data' => $resep
            ],200);
        }

        return response([
            'message' => 'delete resep failed',
            'data' => null,
        ],400);
    }
}
