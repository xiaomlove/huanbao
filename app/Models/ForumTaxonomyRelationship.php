<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumTaxonomyRelationship extends Model
{
    protected $table = 'forum_taxonomy_relationships';

    protected $fillable = [
        'taxonomy_id',
        'fid',
        'display_order',
    ];

}
