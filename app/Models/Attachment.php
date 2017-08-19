<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use App\User;

class Attachment extends Model
{
    protected $fillable = [
        'uid',
        'mime_type',
        'dirname',
        'basename',
        'size',
    ];
    
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'uid');
    }
    
    /**
     * 附件依附于的评论
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attached()
    {
        return $this->belongsToMany(
            Comment::class,
            AttachmentRelationship::TABLE_NAME,
            'attachment_id',
            'target_id'
        );
    }
}
