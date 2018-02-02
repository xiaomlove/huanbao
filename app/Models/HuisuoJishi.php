<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HuisuoJishi extends Model
{
    const TYPE_HUISUO = 'huisuo';

    const TYPE_JISHI = 'jishi';

    protected $table = 'huisuo_jishi_bases';

    private static $typeNames = [
        self::TYPE_JISHI => ['name' => 'JS'],
        self::TYPE_HUISUO => ['name' => 'HS'],
    ];

    protected $fillable = [
        'type',
        'tid',
        'name',
        'short_name',
        'province',
        'city',
        'district',
        'address',
        'background_image',
    ];

    public function isHuisuo()
    {
        return $this->type == self::TYPE_HUISUO;
    }

    public function isJishi()
    {
        return $this->type == self::TYPE_JISHI;
    }

    public function getTypeNameAttribute()
    {
        return self::$typeNames[$this->type]['name'];
    }
}
