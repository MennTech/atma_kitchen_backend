<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Detail_Resep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Resep;
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

    public function showDetail(string $id)
    {
        $resep = Resep::with('detail_resep')->find($id);
        if(!$resep){
            return response([
                'message' => 'resep not found',
                'data' => null
            ],404);
        }
        return response([
            'message' => 'resep found',
            'data' => $resep
        ],200);
    }

    public function store(Request $request){
        $storeData = $request->all();

        $validator = Validator::make($storeData, [
            'nama_resep' => 'required',
            'detail_resep' => 'array',
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }

        //untuk validator tidak bisa memilih bahan baku yang sama dari front end

        $resep = Resep::create([
            'nama_resep' => $storeData['nama_resep']
        ]);
        if($request->has('detail_resep')){
            foreach($storeData['detail_resep'] as $detail){
                Detail_Resep::create([
                    'id_resep' => $resep->id_resep,
                    'id_bahan_baku' => $detail['id_bahan_baku'],
                    'jumlah_bahan' => $detail['jumlah_bahan']
                ]);
            }
        }
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
            'nama_resep' => 'required',
            'detail_resep' => 'array'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $resep->update([
            'nama_resep' => $updateData['nama_resep']
        ]);

        if ($request->has('detail_resep')) {
            Detail_Resep::where('id_resep',$id)->delete();
            foreach($updateData['detail_resep'] as $detail){
                Detail_Resep::create([
                    'id_resep' => $resep->id_resep,
                    'id_bahan_baku' => $detail['id_bahan_baku'],
                    'jumlah_bahan' => $detail['jumlah_bahan']
                ]);
            }
        }
        return response([
            'message' => 'resep updated',
            'data' => $resep
        ],200);

    }

    public function destroyResep($id){
        $resep=Resep::find($id);
        if(!$resep){
            return response([
                'message' => 'resep not found',
                'status' => false,
                'data' => null
            ],404);
        }

        Detail_Resep::where('id_resep',$id)->delete();
        if($resep->delete()){
            return response([
                'message' => 'resep deleted',
                'status' => true,
                'data' => $resep
            ],200);
        }
    }

    // public function destroyAllDetail($id){
    //     Detail_Resep::where('id_resep',$id)->delete();
    //     $detailResep = Detail_Resep::where('id_resep',$id)->get();
    //     return response([
    //         'message' => 'all detail resep deleted',
    //         'data' => $detailResep
    //     ],200);
    // }

    // public function destroyDetail($id,$id2){
    //     Detail_Resep::where('id_resep',$id)->where('id_bahan_baku',$id2)->delete();
    //     $detailResep = Detail_Resep::where('id_resep',$id)->get();
    //     return response([
    //         'message' => 'detail resep deleted',
    //         'data' => $detailResep
    //     ],200);

    // }
}