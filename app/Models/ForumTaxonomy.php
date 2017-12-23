<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumTaxonomy extends Model
{
    protected $table = 'forum_taxonomies';

    protected $fillable = [
        'name',
    ];

    public function forums()
    {
        return $this->belongsToMany(
            Forum::class,
            app(ForumTaxonomyRelationship::class)->getTable(),
            'taxonomy_id',
            'fid'
        );
    }
}
