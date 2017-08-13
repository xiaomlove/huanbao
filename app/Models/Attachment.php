<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'uid',
        'mime_type',
        'dirname',
        'basename',
        'size',
    ];
}
