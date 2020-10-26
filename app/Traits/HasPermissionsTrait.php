<?php

namespace App\Traits;

use App\Permission;
use App\Role;

trait HasPermissionsTrait {

     public function givePermissionsTo(... $permissions) {

        $permissions = $this->getAllPermissions($permissions);
        // dd($permissions);
        if($permissions === null) {
            return $this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }

    public function withdrawPermissionsTo( ... $permissions ) {

        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;

    }

    public function refreshPermissions( ... $permissions ) {

        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }

    public function hasPermissionTo($permissions) {
        // dd($permissions);
        $can = false;

        foreach ($permissions as $permission){
            // echo $permission . ' '. (bool) ($this->hasPermissionThroughRole($permission) ||  $this->hasPermission($permission)) . ' ';
            if((bool) $this->hasPermissionThroughRole($permission) || (bool) $this->hasPermission($permission)){
                $can = true;
            }
        }

        // echo $can;
        return $can;
    }

    public function hasPermissionThroughRole(... $permission) {
        $perm = Permission::whereIn('slug', $permission)->first();

        foreach ($perm->roles as $role){
            if($this->roles->contains('slug', $role->slug)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole( ... $roles ) {

        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    public function roles() {

        return $this->belongsToMany(Role::class,'user_role');

    }
    public function permissions() {

        return $this->belongsToMany(Permission::class,'user_permission');

    }
    protected function hasPermission($permission) {

        return (bool) $this->permissions->where('slug', $permission)->count();
    }

    protected function getAllPermissions(array $permissions) {

        return Permission::whereIn('slug',$permissions)->get();

    }

}