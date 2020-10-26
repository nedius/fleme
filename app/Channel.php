<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Channel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'type', 'position',
    ];

    public function users(){
        return $this->belongsToMany(user::Class);
    }

    public function messages(){
        return $this->belongsToMany(User::Class);
    }
}
