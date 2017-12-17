<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactRelationship extends Model
{
    const OWNER_TYPE_HUISUO = HuisuoJishi::TYPE_FLAG_HUISUO;
    
    const OWNER_TYPE_JISHI = HuisuoJishi::TYPE_FLAG_JISHI;
    
    const TABLE_NAME = 'contact_relationships';
    
    protected $table = self::TABLE_NAME;
    
    protected $fillable = [
        'owner_type',
        'owner_id',
        'contact_id',
        'priority',
    ];
}
