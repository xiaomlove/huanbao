<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumTaxonomyRelationship extends Model
{
    protected $fillable = [
        'taxonomy_id',
        'fid',
        'display_order',
    ];

}
