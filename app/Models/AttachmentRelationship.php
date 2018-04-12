<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttachmentRelationship extends Model
{
    const TARGET_TYPE_COMMENT_DETAIL = 'comment_detail';
    
    const TARGET_TYPE_USER_AVATAR = 'user_avatar';
    
    const TABLE_NAME = 'attachment_relationships';
    
    protected $table = self::TABLE_NAME;
    
    protected $fillable = [
        'target_type', 
        'target_id', 
        'attachment_id',
    ];
    
    
}
