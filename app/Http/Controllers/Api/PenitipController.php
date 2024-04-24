<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penitip;
use Illuminate\Support\Facades\Validator;
class PenitipController extends Controller
{
    public function index(){
        $penitip = Penitip::all();
        if($penitip->isEmpty()){
            return response([
                'message' => 'data empty',
                'data' => null
            ],404);
        }
        return response([
            'message' => 'all Penitip retrived',
            'data' => $penitip
        ],200);
    }

    public function show(string $id){
        $penitip = Penitip::find($id);
        if(!$penitip){
            return response([
                'message' => 'Penitip not found',
                'data' => null
            ],404);
        }
        return response([
            'message' => 'Penitip found',
            'data' => $penitip
        ],200);
    }

    public function store(Request $request){
        $storeData = $request->all();

        $validator = Validator::make($storeData, [
            'nama_penitip' => 'required',
            'no_telp' => 'required'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $penitip = Penitip::create($storeData);
        return response([
            'message' => 'success insert data',
            'data' => $penitip
        ],200);
    }

    public function update(Request $request, string $id){
        $penitip = Penitip::find($id);
        if(!$penitip){
            return response([
                'message' => 'Penitip not found',
                'data' => null
            ],404);
        }
        $updateData = $request->all();
        $validator = Validator::make($updateData, [
            'nama_penitip' => 'required',
            'no_telp' => 'required'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $penitip->nama_penitip = $updateData['nama_penitip'];
        $penitip->no_telp = $updateData['no_telp'];
        $penitip->save();
        return response([
            'message' => 'Penitip updated',
            'data' => $penitip
        ],200);
    }

    public function destroy($id){
        $penitip=Penitip::find($id);
        if(!$penitip){
            return response([
                'message' => 'Penitip not found',
                'data' => null
            ],404);
        }
        $penitip->delete();
        return response([
            'message' => 'Penitip deleted'
        ],200);
    }
}
