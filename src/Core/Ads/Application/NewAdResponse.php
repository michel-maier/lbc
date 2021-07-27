<?php

namespace App\Core\Ads\Application;

class NewAdResponse
{
    private string $id;
    private string $title;
    private string $content;
    private string $type;
    private ?string $model;
    private ?string $manufacturer;

    public function __construct(string $id, string $title, string $content, string $type, ?string $model = null, ?string $manufacturer = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->type = $type;
        $this->model = $model;
        $this->manufacturer = $manufacturer;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }
}