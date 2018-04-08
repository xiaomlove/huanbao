<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cnarea;

class CommonController extends Controller
{
    public function province()
    {
        $list = Cnarea::where("level", 0)->get(['id', 'name']);
        return normalize(0, "OK", $list->toArray());
    }
    
    public function city()
    {
        $pid = request('pid');
        if (empty($pid))
        {
            return normalize("no pid");
        }
        $list = Cnarea::where("level", 1)->where('parent_id', $pid)->get(['id', 'name']);
        return normalize(0, "OK", $list->toArray());
    }
    
    public function district()
    {
        $pid = request('pid');
        if (empty($pid))
        {
            return normalize("no pid");
        }
        $list = Cnarea::where("level", 2)->where('parent_id', $pid)->get(['id', 'name']);
        return normalize(0, "OK", $list->toArray());
    }

}
