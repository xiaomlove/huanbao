<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentDetail extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['cid', 'content'];
}
