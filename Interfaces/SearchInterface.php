<?php

namespace App\Services\Search\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface SearchInterface
{
    /**
     * @return void
     */
    public function createIndex(): void;

    /**
     * @return void
     */
    public function deleteIndex(): void;

    /**
     * @param string $term
     * @return Collection|null
     */
    public function findByTerm(string $term): ?array;

    /**
     * @return string
     */
    public function reindex(): string;

    /**
     * @param Model $model
     * @return mixed
     */
    public function createIndexRecord(Model $model): void;

    /**
     * @param Model $model
     * @return mixed
     */
    public function deleteIndexRecord(Model $model): void;
}
