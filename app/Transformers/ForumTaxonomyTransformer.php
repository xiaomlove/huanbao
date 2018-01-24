<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\ForumTaxonomy;

class ForumTaxonomyTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['forums'];
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(ForumTaxonomy $forumTaxonomy)
    {
        return [
            'id' => $forumTaxonomy->id,
            'key' => $forumTaxonomy->id,
            'name' => $forumTaxonomy->name,
        ];
    }

    public function includeForums(ForumTaxonomy $forumTaxonomy)
    {
        $forums = $forumTaxonomy->forums;
        if ($forums->isNotEmpty())
        {
            return $this->collection($forums, new ForumTransformer());
        }
    }
}
