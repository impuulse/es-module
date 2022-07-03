<?php

namespace App\Services\Search\Handlers;

use App\Services\Search\Jobs\SaveUnsatisfiedSearchRequestJob;

class UnsatisfiedSearchRequestHandler
{
    /**
     * Проверка и сохранение неудовлетворенного запроса
     * @param $results
     * @param $term
     */
    public function handle($results, $term): void
    {
        if ($this->hasRequest($results)) {
            $this->saveRequest($term);
        }
    }

    /**
     * Проверка на неудовлетворенный запрос
     * @param $results
     * @return bool
     */
    private function hasRequest($results): bool
    {
        $unsatisfiedConditionCount = 0;

        foreach ($results as $result) {
            if (is_null($result)) {
                $unsatisfiedConditionCount++;
            }
        }

        return $unsatisfiedConditionCount === count($results);
    }

    /**
     * Фоновое сохранение неудовлетворенного запроса
     * @param $term
     */
    private function saveRequest($term): void
    {
        SaveUnsatisfiedSearchRequestJob::dispatch($term);
    }
}
