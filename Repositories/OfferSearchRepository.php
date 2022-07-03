<?php

namespace App\Services\Search\Repositories;

use App\Models\Offer;

class OfferSearchRepository extends ElasticSearchRepository
{
    public function __construct(Offer $model)
    {
        parent::__construct($model);
    }

    protected array $fieldsForDocument = [
        'id',
        'full_name',
        'slug',
        'searchContents'
    ];

    protected array $searchableFields = [
        'full_name',
        'searchContents'
    ];

    protected array $fieldsForSearchResult = [
        'id',
        'full_name',
        'slug'
    ];

    protected array $mapping = [
        'properties' => [
            'id' => [
                'type' => 'long'
            ],
            'full_name' => [
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
