<?php

namespace App\Core\Ads\Application;

class NewAdRequest
{
    private string $title;
    private string $content;
    private string $type;
    private ?string $model;

    public function __construct(string $title, string $content, string $type, string $model = null)
    {
        $this->title = $title;
        $this->content = $content;
        $this->type = $type;
        $this->model = $model;
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
}
