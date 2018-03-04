<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Topic;

class Forum extends Model
{
    const JISHI = 1;

    const HUISUO = 2;

    protected $fillable = [
        'id',
        'key',
        'icon',
        'name',
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

    /**
     * 所有属于的分类法
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function taxonomies()
    {
        return $this->belongsToMany(
            ForumTaxonomy::class,
            app(ForumTaxonomyRelationship::class)->getTable(),
            'fid',
            'taxonomy_id'
        );
    }

    /**
     * 版块通过话题远程关联评论
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function comments()
    {
        return $this->hasManyThrough(
            Comment::class,
            Topic::class,
            "fid",//中间模型Topic外键名
            "tid",//最终模型Comment外键名
            "id",//Forum本地键
            "id"//中间模型Topic本地键名
        );
    }

}
