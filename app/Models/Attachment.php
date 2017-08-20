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
    
    public function users()
    {
        return $this->morphedByMany(
            User::class, 
            'target',
            AttachmentRelationship::TABLE_NAME,
            'attachment_id',
            'target_id'
        );
    }
    
    public function comments()
    {
        return $this->morphedByMany(
            Comment::class,
            'target',
            AttachmentRelationship::TABLE_NAME,
            'attachment_id',
            'target_id'
        );
    }
}
