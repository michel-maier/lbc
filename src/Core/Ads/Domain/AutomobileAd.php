<?php

namespace App\Core\Ads\Domain;

class AutomobileAd extends Ad
{
    private string $model;
    private string $manufacturer;

    public function __construct(string $title, string $content, string $model, string $manufacturer)
    {
        parent::__construct($title, $content);

        $this->model = $model;
        $this->manufacturer = $manufacturer;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getManufacturer(): string
    {
        return $this->manufacturer;
    }

    public function getType(): string
    {
        return self::AUTOMOBILE_TYPE;
    }
}
