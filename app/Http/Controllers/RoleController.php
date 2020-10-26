<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Channel;
use App\Role;
use App\Permission;

use App\Http\Resources\Role as RoleResource;
use App\Http\Resources\Permission as PermissionResource;

use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function getAllRole(Request $request){
        return RoleResource::collection(Role::all());
    }

    public function getAllPermissions(Request $request){
        return PermissionResource::collection(Permission::all());
    }

    public function editRole(Request $request, $roleId){
        $role = Role::findOrFail($roleId);

        if($request->permissions){
            $permissions = $request->permissions;

            $role->permissions()->sync($permissions);
        }

        if($request->name){
            $this->validate($request, [
                "name" => "required|min:1|max:32",
            ]);

            $role->name = $request->name;
            $role->slug = Str::slug($request->name, '-');
            $role->save();
        }

        return new RoleResource($role);
    }

    public function createRole(Request $request){
        // $role = new Role;

        $this->validate($request, [
            "name" => "required|min:1|max:32",
        ]);

        $name = $request->name;
        $slug = Str::slug($request->name, '-');
        $role = Role::create(['name' => $name, 'slug' => $slug]);

        return response($role , 201);
    }

    public function deleteRole(Request $request, $roleId){
        $role = Role::findOrFail($roleId);

        $role->delete();

        return response($role , 200);
    }
    
}
