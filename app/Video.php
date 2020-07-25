<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
    protected $table = 'uservideos';
    protected $fillable = ['email','title','msg','videokey','name','imgkey'];
}
