<?php

namespace App\Services\Search;

use App\Services\Logs\LogService;
use App\Services\Search\DTO\ApiSearchDTO;
use App\Services\Search\Repositories\SatisfiedSearchRequestsRepository;
use App\Services\Search\Translators\ApiSearchTranslator;
use App\Services\Search\Handlers\UnsatisfiedSearchRequestHandler;
use App\Services\Search\Repositories\CategorySearchRepository;
use App\Services\Search\Repositories\OfferSearchRepository;
use App\Services\Search\Repositories\ProductSetSearchRepository;
use Exception;

/**
 * Class SearchService
 * @package App\Services\Search
 */
class SearchService
{
    public function __construct(
        private CategorySearchRepository $categorySearchRepository,
        private ProductSetSearchRepository $productSetSearchRepository,
        private OfferSearchRepository $offerSearchRepository,
        private UnsatisfiedSearchRequestHandler $unsatisfiedSearchRequestHandler,
        private ApiSearchTranslator $apiSearchTranslator,
        private SatisfiedSearchRequestsRepository $satisfiedSearchRequestsRepository,
        private LogService $logService,
    ) {}

    /**
     * Полнотекстовый поиск
     * @param $term
     * @return ApiSearchDTO
     * @throws Exception
     */
    public function search($term): ApiSearchDTO
    {
        $categories = $this->categorySearchRepository->findByTerm($term);
        $productSets = $this->productSetSearchRepository->findByTerm($term);
        $offers = $this->offerSearchRepository->findByTerm($term);

        $results = [
            'categories' => $categories,
            'productSets' => $productSets,
            'offers' => $offers
        ];

        $this->unsatisfiedSearchRequestHandler->handle($results, $term);

        return $this->apiSearchTranslator->translate($results);
    }

    /**
     * Сохранение удовлетворенного результата
     * @param $term
     */
    public function createSatisfiedRequest($term): void
    {
        $this->satisfiedSearchRequestsRepository->create(['term' => $term]);
    }
}
