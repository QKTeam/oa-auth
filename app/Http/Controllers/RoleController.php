<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Validator;

class RoleController extends Controller
{
    public function createRole (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:role,name',
            'short_name' => 'required|unique:role,short_name',
            'icon' => 'required',
            'color' => [
                'required',
                'regex:/^#[a-fA-F0-9]{6}$/',
            ],
        ]);
        if ($validator->fails()) return response($validator->errors(), 422);
        $role = new Role();
        $role->name = $request->input('name');
        $role->short_name = $request->input('short_name');
        $role->icon = $request->input('icon');
        $role->color = $request->input('color');
        $role->save();
        return response([
            'status' => 1,
        ]);
    }
    public function delete (Request $request) {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
        ]);
        if ($validator->fails()) return response($validator->errors(), 422);
        $role = Role::find($request->input('role_id'));
        $role->delete();
        return response([
            'status' => 1,
        ]);
    }
}
