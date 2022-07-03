<?php

namespace App\Services\Search\DTO;

use App\Services\DTO\DTO;

class ApiSearchProductSetDTO extends DTO
{
    protected int $id;
    protected string $name;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }
}
