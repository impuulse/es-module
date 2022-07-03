<?php

namespace App\Services\Search\Repositories;

use App\Models\Category;

class CategorySearchRepository extends ElasticSearchRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    protected array $fieldsForDocument = [
        'id',
        'name',
        'slug',
        'searchContents'
    ];

    protected array $searchableFields = [
        'name',
        'searchContents'
    ];

    protected array $fieldsForSearchResult = [
        'id',
        'name',
        'slug'
    ];

    protected array $mapping = [
        'properties' => [
            'id' => [
                'type' => 'long'
            ],
            'name' => [
                'type' => 'text',
                'analyzer' => 'name_analyzer'
            ],
            'slug' => [
                'type' => 'text'
            ],
            'searchContents' => [
                'type' => 'text'
            ],
        ]
    ];
}
