<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['uid', 'tid', 'pid', 'floor_num'];
    
    public $timestamps = false;
}
