<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type', 'url', 'message_id', 'width', 'height',
    ];

    public function message(){
        return $this->hasOne(Message::Class, 'id', 'message_id');
    }
}
