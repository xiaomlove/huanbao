<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumTaxonomy extends Model
{
    protected $fillable = [
        'name',
    ];

    public function forums()
    {
        return $this->belongsToMany(
            Forum::class,
            ForumTaxonomyRelationship::class,
            'fid',
            'taxonomy_id'
        );
    }
}
