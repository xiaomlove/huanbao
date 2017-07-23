<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    protected $fillable = ['name', 'slug', 'pid', 'description', 'display_order'];
    
    public function tt()
    {
        echo __METHOD__;
    }
    
    public function listTree(array $params = [])
    {
        $defaults = [
            'max_depth' => null,
        ];
        $args = array_merge($defaults, $params);
        $list = self::all();
        $tree = self::buildTree($list, 0, 0, $args['max_depth']);
        return $tree;
    }
    
    public function listTreeOneDimensional()
    {
        $list = self::all();
        $tree = self::buildTreeOneDimensional($list);
        return $tree;
    }
    
    private static function buildTree($resource, $pid = 0, $depth = 0, $maxDepth = null)
    {
        $out = [];
        if (!is_null($maxDepth) && $depth > $maxDepth)
        {
            return [];
        }
        foreach ($resource as $item)
        {
            if ($item->pid == $pid)
            {
                $item->depth = $depth;
                $children = self::buildTree($resource, $item->id, $depth + 1, $maxDepth);
                if (empty($children))
                {
                    $item->is_leaf = true;
                }
                else
                {
                    $item->is_leaf = false;
                }
                $item->children = $children;
                $out[] = $item;
            }
        }
        return $out;
    }
    
    private static function buildTreeOneDimensional($resource, $pid = 0, $depth = 0)
    {
        static $out = [];
        foreach ($resource as $item)
        {
            if ($item->pid == $pid)
            {
                $item->depth = $depth;
                $out[] = $item;
                self::buildTreeOneDimensional($resource, $item->id, $depth + 1);
            }
        }
        return $out;
    }
}
