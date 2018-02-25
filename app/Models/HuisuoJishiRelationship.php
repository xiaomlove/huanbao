<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HuisuoJishiRelationship extends Model
{
    protected $table = 'huisuo_jishi_relationships';

    protected $fillable = ['huisuo_id', 'huisuo_name', 'jishi_id', 'jishi_name', 'begin_time', 'end_time'];
}
