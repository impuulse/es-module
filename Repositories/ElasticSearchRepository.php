<?php

namespace App\Services\Search\Repositories;

use App\Services\Search\Interfaces\SearchInterface;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

abstract class ElasticSearchRepository implements SearchInterface
{
    protected Model $model;
    protected Builder $builder;
    protected array $fieldsForDocument;
    protected array $searchableFields;
    protected array $fieldsForSearchResult;
    protected array $mapping;
    protected array $settings = [
        'index' => [
            'number_of_shards' => 1,
            'analysis' => [
                'filter' => [
                    'stemmer' => [
                        'type' => 'stemmer',
                        'language' => 'russian'
                    ],
                    'my_ngram' => [
                        'type' => 'edge_ngram',
                        'min_gram' => '3',
                        'max_gram' => '10',
                    ],
                    'stop_words' => [
                        'type' => 'stop',
                        'stopwords' => '_russian_'
                    ],
                    'my_test_dict_stemmer' => [
                        'type' => 'hunspell',
                        'locale' => 'ru_RU',
                        'dedup' => false
                    ]
                ],
                'analyzer' => [
                    'name_analyzer' => [
                        'type' => 'custom',
                        'tokenizer' => 'standard',
                        'filter' => [
                            'lowercase',
                            'trim',
                            'stemmer',
                            'my_ngram',
                            'stop_words',
                            'my_test_dict_stemmer',
                            'russian_morphology'
                        ]
                    ]
                ],
            ]
        ]
    ];

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->builder = $model->newQuery();
    }

    private function getElasticSearchClient(): Client
    {
        return app(Client::class);
    }

    /**
     * Создание индекса
     */
    public function createIndex(): void
    {
        $this->getElasticSearchClient()->indices()->create([
            'index' => $this->model->getTable(),
            'body' => [
                'settings' => $this->getSettings(),
                'mappings' => $this->getMapping()
            ]
        ]);
    }

    /**
     * Удаление индекса
     */
    public function deleteIndex(): void
    {
        $indexExists = $this->getElasticSearchClient()->indices()->exists([
            'index' => $this->model->getTable()
        ]);

        if ($indexExists) {
            $this->getElasticSearchClient()->indices()->delete([
                'index' => $this->model->getTable()
            ]);
        }
    }

    /**
     * Реиндексация
     * @return string
     */
    public function reindex(): string
    {
        try {
            $this->deleteIndex();
            $this->createIndex();

            foreach ($this->builder->cursor() as $item) {
                /** @var $item Model */
                $this->getElasticSearchClient()->index([
                    'index' => $item->getTable(),
                    'id' => $item->getKey(),
                    'body' => $this->getFieldsForDocument($item)
                ]);
            }

            $message = 'Индексация прошла успешно!';
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $message = "Ошибка при индексации: $message";
        }

        return $message;
    }

    /**
     * Добавление записи в индекс
     * @param Model $model
     * @return void
     */
    public function createIndexRecord(Model $model): void
    {
        $this->getElasticSearchClient()->index([
            'index' => $model->getTable(),
            'id' => $model->getKey(),
            'body' => $this->getFieldsForDocument($model)
        ]);
    }

    /**
     * Удаление записи в индексе
     * @param Model $model
     * @return void
     */
    public function deleteIndexRecord(Model $model): void
    {
        $this->getElasticSearchClient()->delete([
            'index' => $model->getTable(),
            'id' => $model->getKey(),
        ]);
    }

    /**
     * Поиск по условию
     * @param string $term
     * @return array|null
     */
    public function findByTerm(string $term): ?array
    {
        $searchResults = $this->getElasticSearchClient()->search([
            'index' => $this->model->getTable(),
            'size' => 20,
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => $this->getSearchableFields(),
                        'query' => $term,
                        'fuzziness' => 'AUTO'
                    ]
                ],
            ],
        ]);

        return $this->buildCollection($searchResults);
    }

    /**
     * Формирование коллекции по результату поиска
     * @param array $items
     * @return array|null
     */
    private function buildCollection(array $items): ?array
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        if (!$ids) {
            return [];
        }

        $collection = $this->builder->findMany($ids)
            ->sortBy(function ($model) use ($ids) {
                /** @var $model Model */
                return array_search($model->getKey(), $ids);
            });

        $data = $collection->map(function ($item) {
            /** @var $item Model */
            return $item->only($this->getFieldsForSearchResult());
        })->toArray();

        return array_values($data);
    }

    /**
     * Поля, сохраняемые в документ
     * @param Model $model
     * @return array
     */
    private function getFieldsForDocument(Model $model): array
    {
        $result = [];

        foreach ($this->fieldsForDocument as $field) {
            $result[$field] = $model->$field;
        }

        return $result;
    }

    /**
     * Поля, используемые в поиске
     * @return string[]
     */
    private function getSearchableFields(): array
    {
        return $this->searchableFields;
    }

    /**
     * Поля, используемые в поисковой выдаче
     * @return array
     */
    private function getFieldsForSearchResult(): array
    {
        return $this->fieldsForSearchResult;
    }

    /**
     * Мэппинг для индекса
     * @return array
     */
    private function getMapping(): array
    {
        return $this->mapping;
    }

    /**
     * Настройки для индекса
     * @return array
     */
    private function getSettings(): array
    {
        return $this->settings;
    }
}
