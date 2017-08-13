<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttachmentRelationship extends Model
{
    const TARGET_TYPE_COMMENT = 'comment';
    
    const TABLE_NAME = 'attachment_relationships';
    
    protected $table = self::TABLE_NAME;
    
    protected $fillable = [
        'target_type', 
        'target_id', 
        'attachment_id',
        'priority',
    ];
    
    
}
