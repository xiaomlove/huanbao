<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HuisuoJishiRelationship extends Model
{
    const TABLE_NAME = 'huisuo_jishi_relationships';
    
    protected $table = self::TABLE_NAME;
    
    protected $fillable = [
        'huisuo_id',
        'jishi_id',
        'priority',
    ];
}
