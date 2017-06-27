<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forums extends Model
{
    protected $fillable = ['name', 'pid', 'description', 'display_order'];
    
    
}
