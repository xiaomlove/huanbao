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
        'key',
        'size',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }

    public function commentDetails()
    {
        return $this->morphedByMany(
            CommentDetail::class,
            "target",
            AttachmentRelationship::TABLE_NAME,
            "attachment_key",
            "target_id",
            "key",
            "cid"
        );
    }
    
}
