<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index(){
        $role = Role::where('jabatan', '!=', 'Owner')->get();
        if($role->isEmpty()){
            return response([
                'message' => 'data empty',
                'data' => null
            ],404);
        }
        return response([
            'message' => 'all role retrived',
            'data' => $role
        ],200);
    }

    public function show(string $id){
        $role = Role::find($id);
        if(!$role){
            return response([
                'message' => 'role not found',
                'data' => null
            ],404);
        }
        return response([
            'message' => 'role found',
            'data' => $role
        ],200);
    }

    public function store(Request $request){
        $storeData = $request->all();

        $validator = Validator::make($storeData, [
            'jabatan' => 'required',
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $storeData['gaji'] = 0;
        $role = Role::create($storeData);
        return response([
            'message' => 'success insert data',
            'data' => $role
        ],200);
    }

    public function update(Request $request, string $id){
        $role = Role::find($id);
        if(!$role){
            return response([
                'message' => 'role not found',
                'data' => null
            ],404);
        }
        $updateData = $request->all();
        $validator = Validator::make($updateData, [
            'jabatan' => 'required'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $role->jabatan = $updateData['jabatan'];
        $role->save();
        return response([
            'message' => 'success update data',
            'data' => $role
        ],200);
    }

    public function updateGaji(Request $request, string $id){
        $role = Role::find($id);
        if(!$role){
            return response([
                'message' => 'role not found',
                'data' => null
            ],404);
        }
        $updateData = $request->all();
        $validator = Validator::make($updateData, [
            'gaji' => 'required'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $role->gaji = $updateData['gaji'];
        $role->save();
        return response([
            'message' => 'success update data',
            'data' => $role
        ],200);
    }

    public function destroy(string $id){
        $role = Role::find($id);
        if(!$role){
            return response([
                'message' => 'role not found',
                'status' => false,
                'data' => null
            ],404);
        }
        $role->delete();
        return response([
            'message' => 'success delete role',
            'status' => true,
            'data' => $role
        ],200);
    }
}
