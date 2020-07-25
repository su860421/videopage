<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Msgsub extends Model
{
    //
    protected $table = 'msg';
    protected $fillable = ['email','title','msg','videokey'];
}
