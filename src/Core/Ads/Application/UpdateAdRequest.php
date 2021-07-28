<?php

namespace App\Core\Ads\Application;

class UpdateAdRequest
{
    private ?string $id;
    private ?string $title;
    private ?string $content;
    private ?string $model;

    public function __construct(?string $id = null, ?string $title = null, ?string $content = null, ?string $model = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->model = $model;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }
}