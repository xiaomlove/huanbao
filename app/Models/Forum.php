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
    
    public function listTree()
    {
        $list = self::all();
        $tree = self::buildTree($list);
        return $tree;
    }
    
    public function listTreeOneDimensional()
    {
        $list = self::all();
        $tree = self::buildTreeOneDimensional($list);
        return $tree;
    }
    
    private static function buildTree($resource, $pid = 0, $depth = 0)
    {
        $out = [];
        foreach ($resource as $item)
        {
            if ($item->pid == $pid)
            {
                $item->depth = $depth;
                $children = self::buildTree($resource, $item->id, $depth + 1);
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
