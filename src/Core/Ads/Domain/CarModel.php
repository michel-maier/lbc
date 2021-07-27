<?php

namespace App\Core\Ads\Domain;

class CarModel
{
    private CarModelId $id;
    private string $name;
    private string $manufacturer;

    public function __construct(string $name, string $manufacturer)
    {
        $this->id = new CarModelId();
        $this->name = $name;
        $this->manufacturer = $manufacturer;
    }

    public function getId(): CarModelId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getManufacturer(): string
    {
        return $this->manufacturer;
    }

    public function isMatchingTheSearchString(string $search): bool
    {
        if (str_contains($this->sanitize($search), $this->sanitize($this->name))) {
            return true;
        }

        return false;
    }

    private function sanitize(string $subject): string
    {
        return strtolower(preg_replace('/\s+/', '', $subject));
    }
}