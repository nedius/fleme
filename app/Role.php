<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $hidden = [
        'pivot'
    ];

    protected $fillable = [
        'name', 'slug',
    ];

    public function getRole() {
        return [
            "id" => $this->id,
            "name" => $this->name,
        ];
    }

    public function permissions() {
        return $this->belongsToMany(Permission::class,'permission_role');
    }
     
    public function users() {
        return $this->belongsToMany(User::class,'user_role');
    }
}
