<?php

namespace App\Services\Search\DTO;

use App\Services\DTO\DTO;

class ApiSearchOfferDTO extends DTO
{
    protected int $id;
    protected string $full_name;
    protected string $slug;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->full_name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }
}
