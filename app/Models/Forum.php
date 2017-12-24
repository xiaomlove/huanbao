<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Topic;

class Forum extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];
    
    /**
     * 一个版块有多个主题
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(Topic::class, 'fid', 'id');
    }

    public function taxonomies()
    {
        return $this->belongsToMany(
            ForumTaxonomy::class,
            app(ForumTaxonomyRelationship::class)->getTable(),
            'fid',
            'taxonomy_id'
        );
    }

}
