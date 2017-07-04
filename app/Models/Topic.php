<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['title', 'fid', 'uid'];
    
    public $timestamps = false;
    
    
}
