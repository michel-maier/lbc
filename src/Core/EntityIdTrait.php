<?php

namespace App\Core;

trait EntityIdTrait
{
    private string $id;

    public function __construct(?string $id = null)
    {
        null !== $id && !uuid_is_valid($id) && throw new DomainException(sprintf('"%s" is not a valid uuid', $id));
        $this->id = $id ?? uuid_create(UUID_TYPE_NAME);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}