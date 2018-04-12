<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class CommentDetail extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['cid', 'content'];
    
    protected $touches = ['comment'];//联动更新comment()方法中模型

    const CONTENT_TYPE_TEXT = 'text';
    const CONTENT_TYPE_IMAGE = 'image';
    
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'cid', 'id');
    }

    public function attachments()
    {
        return $this->morphToMany(
            Attachment::class,
            "target",
            AttachmentRelationship::TABLE_NAME,
            "target_id",
            "attachment_id",
            "cid",
            "key"
        );
    }

}
