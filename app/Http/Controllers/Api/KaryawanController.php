<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Presensi;
use Illuminate\Support\Facades\Validator;
class KaryawanController extends Controller
{
    public function index(){
        $karyawan = Karyawan::with('role')->whereHas('role', function ($query){
            $query->whereNotIn('jabatan', ['Owner']);
        })->get();
        if($karyawan->isEmpty()){
            return response([
                'message' => 'data empty',
                'data' => null
            ],404);
        }
        return response([
            'message' => 'all karyawan retrived',
            'data' => $karyawan
        ],200);
    }

    public function show(string $id){
        $karyawan = Karyawan::find($id);
        if(!$karyawan){
            return response([
                'message' => 'karyawan not found',
                'data' => null
            ],404);
        }
        return response([
            'message' => 'karyawan found',
            'data' => $karyawan
        ],200);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validator = Validator::make($storeData, [
            'id_role' => 'required',
            'nama_karyawan' => 'required',
            'email_karyawan' => 'required',
            'no_telp' => 'required|between:10,13',
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $storeData['bonus'] = 0;
        if($request->has('password')){
            $storeData['password'] = $request->password;
        }
        $karyawan = Karyawan::create($storeData);
        return response([
            'message' => 'success insert data',
            'data' => $karyawan
        ],200);
    }

    public function update(Request $request, string $id){
        $karyawan = Karyawan::find($id);
        if(!$karyawan){
            return response([
                'message' => 'karyawan not found',
                'data' => null
            ],404);
        }
        $updateData = $request->all();
        $validator = Validator::make($updateData, [
            'id_role' => 'required',
            'nama_karyawan' => 'required',
            'email_karyawan' => 'required',
            'no_telp' => 'required',
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        if($request->has('password')){
            $updateData['password'] = $request->password;
            $karyawan->password = $updateData['password'];
        }
        $karyawan->email_karyawan = $updateData['email_karyawan'];
        $karyawan->id_role = $updateData['id_role'];
        $karyawan->nama_karyawan = $updateData['nama_karyawan'];
        $karyawan->no_telp = $updateData['no_telp'];
        $karyawan->save();
        return response([
            'message' => 'karyawan updated',
            'data' => $karyawan
        ],200);
    }   

    public function updateBonus(Request $request, string $id){
        $karyawan = Karyawan::find($id);
        if(!$karyawan){
            return response([
                'message' => 'karyawan not found',
                'data' => null
            ],404);
        }
        $updateData = $request->all();
        $validator = Validator::make($updateData, [
            'bonus' => 'required'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $karyawan->bonus = $updateData['bonus'];
        $karyawan->save();
        return response([
            'message' => 'bonus karyawan updated',
            'data' => $karyawan
        ],200);
    }

    public function destroy($id){
        $karyawan=Karyawan::find($id);
        if(!$karyawan){
            return response([
                'message' => 'karyawan not found',
                'data' => null
            ],404);
        }
        $karyawan->delete();
        Presensi::where('id_karyawan',$id)->delete();
        return response([
            'message' => 'karyawan deleted'
        ],200);
    }
}
