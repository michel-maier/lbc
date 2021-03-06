<?php

namespace App\Core\Ads\Application;

class AdListResponse
{
    private string $id;
    private string $title;
    private string $content;
    private string $type;

    public function __construct(string $id, string $title, string $content, string $type)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->type = $type;
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
}
