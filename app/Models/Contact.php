<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Attachment;

class Contact extends Model
{
    const TYPE_WECHAT = 'wechat';
    const TYPE_QQ = 'qq';
    const TYPE_PHONE = 'phone';
    
    protected $fillable = [
        'type',
        'account',
        'image_id',
    ];
    
    public static function listTypes()
    {
        return [
            self::TYPE_WECHAT => '微信',
            self::TYPE_QQ => 'QQ',
            self::TYPE_PHONE => '电话',
        ];
    }
    
    public function image()
    {
        return $this->hasOne(Attachment::class, 'id', 'image_id');
    }
}
