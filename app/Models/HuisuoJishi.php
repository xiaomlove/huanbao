<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Attachment;

class HuisuoJishi extends Model
{
    const TYPE_FLAG_HUISUO = 'huisuo';
    const TYPE_FLAG_JISHI = 'jishi';
    
    protected $table = 'huisuo_jishis';
    
    protected $fillable = [
        'name',
        'creator',
        'type_flag',
        'cover',
        'province',
        'city',
        'district',
        'address',
        'description',
        'age',
        'price',
    ];
    
    public function coverImage()
    {
        return $this->hasOne(Attachment::class, 'id', 'cover');
    }
    
    /**
     * 拥有的联系方式。其实是多对多
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function contacts()
    {
        return $this->morphToMany(
            Contact::class, 
            'owner', 
            ContactRelationship::TABLE_NAME,
            'owner_id',
            'contact_id'
        );
    }
}
