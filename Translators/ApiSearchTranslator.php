<?php

namespace App\Services\Search\Translators;

use App\Services\Search\DTO\ApiSearchDTO;

class ApiSearchTranslator
{
    public function translate(array $data): ApiSearchDTO
    {
        return ApiSearchDTO::fromArray($data);
    }
}
