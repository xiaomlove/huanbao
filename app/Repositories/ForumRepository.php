<?php

namespace App\Repositories;

use App\Models\Forum;
use Illuminate\Http\Request;

class ForumRepository
{
    protected $forum;
    
    public function __construct(Forum $forum)
    {
        $this->forum = $forum;
    }
    
    public function listAll(Request $request)
    {
        return $this->forum
            ->when($request->name, function ($query) use ($request) {
                return $query->where('name', 'like', "%{$request->name}%");
            })
            ->when($request->taxonomy_id, function ($query) use ($request) {
                return $query->whereHas('taxonomies', function ($query) use ($request) {
                    return $query->where("taxonomy_id", $request->taxonomy_id);
                });
            })
            ->paginate($request->get('per_page', 20));
    }
}