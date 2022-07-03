<?php

namespace App\Services\Search\Repositories;

use App\Models\UnsatisfiedSearchRequest;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\RecordsNotFoundException;

class UnsatisfiedSearchRequestsRepository implements RepositoryInterface
{
    public function create(array $attributes): UnsatisfiedSearchRequest
    {
        return UnsatisfiedSearchRequest::create($attributes);
    }

    public function getOne($id): ?UnsatisfiedSearchRequest
    {
        return UnsatisfiedSearchRequest::find($id);
    }

    public function getAll(): ?Collection
    {
        return UnsatisfiedSearchRequest::all();
    }

    public function update($id, array $attributes): UnsatisfiedSearchRequest
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
}
