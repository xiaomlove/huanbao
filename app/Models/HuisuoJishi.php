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

    public function scopeJishi($query)
    {
        return $query->where('type', self::TYPE_JISHI);
    }

    public function scopeHuisuo($query)
    {
        return $query->where('type', self::TYPE_HUISUO);
    }

    public static function listTypes($string = false)
    {
        if ($string)
        {
            return implode(',', array_keys(self::$typeNames));
        }
        return self::$typeNames;
    }

    public static function getGuessType()
    {
        $currentRouteName = \Route::currentRouteName();
        if (strpos($currentRouteName, 'huisuo') !== false)
        {
            $type = self::TYPE_HUISUO;
        }
        elseif (strpos($currentRouteName, 'jishi') !== false)
        {
            $type = self::TYPE_JISHI;
        }
        else
        {
            return null;
        }
        return self::$typeNames[$type] + ['type' => $type];
    }

    public function getTypeNameAttribute()
    {
        return self::$typeNames[$this->type]['name'];
    }

    public function getTypeNameOppositeAttribute()
    {
        if ($this->isHuisuo())
        {
            return self::$typeNames[self::TYPE_JISHI]['name'];
        }
        elseif ($this->isJishi())
        {
            return self::$typeNames[self::TYPE_HUISUO]['name'];
        }
        else
        {
            return '';
        }
    }

    public function getShortNameLabelAttribute()
    {
        if ($this->isJishi())
        {
            return '工号';
        }
        elseif ($this->isHuisuo())
        {
            return '简称';
        }
        return '';
    }

    /**
     * JS 的全部 HS
     */
    public function huisuos()
    {
        return $this->hasMany(
            HuisuoJishiRelationship::class,
            'jishi_id',
            'id'
        );
    }

    /**
     * HS 的全部 JS
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jishis()
    {
        return $this->hasMany(
            HuisuoJishiRelationship::class,
            "huisuo_id",
            'id'
        );
    }
}
