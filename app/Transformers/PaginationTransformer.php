<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginationTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];
    
    protected $availableIncludes = [];
    
    public function transform(LengthAwarePaginator $paginator)
    {
        return [
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'total_pages' => ceil($paginator->total() / $paginator->perPage()),
            'links' => [
                'next'=> $paginator->nextPageUrl(),
                'previous' => $paginator->previousPageUrl(),
            ]
        ];
    }
}

