<?php

namespace App\Services\Search\DTO;

use App\Services\DTO\DTO;

class ApiSearchDTO extends DTO
{
    /** @var ApiSearchProductSetDTO[] */
    protected array $productSets;
    /** @var ApiSearchCategoryDTO[]  */
    protected array $categories;
    /** @var ApiSearchOfferDTO[] */
    protected array $offers;

    public static function fromArray(array $data): ApiSearchDTO
    {
        $productSets = [];
        $categories = [];
        $offers = [];

        foreach ($data['productSets'] as $productSet) {
            $productSets[] = ApiSearchProductSetDTO::fromArray($productSet);
        }

        foreach ($data['categories'] as $category) {
            $categories[] = ApiSearchCategoryDTO::fromArray($category);
        }

        foreach ($data['offers'] as $offer) {
            $offers[] = ApiSearchOfferDTO::fromArray($offer);
        }

        $data['productSets'] = $productSets;
        $data['categories'] = $categories;
        $data['offers'] = $offers;

        return parent::fromArray($data);
    }

    public function toArray(): array
    {
        $productSets = [];
        $categories = [];
        $offers = [];

        foreach ($this->productSets as $productSet) {
            $productSets[] = $productSet->toArray();
        }

        foreach ($this->categories as $category) {
            $categories[] = $category->toArray();
        }

        foreach ($this->offers as $offer) {
            $offers[] = $offer->toArray();
        }

        $this->productSets = $productSets;
        $this->categories = $categories;
        $this->offers = $offers;

        return parent::toArray();
    }
}
