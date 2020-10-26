<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'content', 'user_id', 'channel_id',
    ];

    public function user(){
        return $this->hasOne(User::Class, 'id', 'user_id');
    }

    public function channel(){
        return $this->hasOne(Channel::Class, 'id', 'channel_id');
    }

    public function files(){
        return $this->hasMany(File::Class);
    }
}
