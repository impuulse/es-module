<?php

namespace App\Services\Search\Repositories;

use App\Models\SatisfiedSearchRequest;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\RecordsNotFoundException;

class SatisfiedSearchRequestsRepository implements RepositoryInterface
{
    public function create(array $attributes): SatisfiedSearchRequest
    {
        $termExistence = self::findByTerm($attributes['term']);

        if ($termExistence) {
            return self::update($termExistence->id, ['count' => $termExistence->count + 1]);
        }

        return SatisfiedSearchRequest::create($attributes);
    }

    public function getOne($id): ?SatisfiedSearchRequest
    {
        return SatisfiedSearchRequest::find($id);
    }

    public function getAll(): ?Collection
    {
        return SatisfiedSearchRequest::all();
    }

    public function update($id, array $attributes): SatisfiedSearchRequest
    {
        $model = self::getOne($id);
        if (is_null($model)) {
            throw new RecordsNotFoundException();
        }
        $model->update($attributes);

        return $model;
    }

    public function remove($id): bool
    {
        $model = self::getOne($id);
        if (is_null($model)) {
            throw new RecordsNotFoundException();
        }

        return $model->delete();
    }

    public function findByTerm($term): ?SatisfiedSearchRequest
    {
        return SatisfiedSearchRequest::where('term', $term)->first();
    }
}
