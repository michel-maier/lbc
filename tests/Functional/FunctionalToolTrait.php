<?php

namespace App\Tests\Functional;

use App\Core\Ads\Domain\Ad;

trait FunctionalToolTrait
{
    private function findOneAdByTitle(string $title): Ad
    {
        return self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository(Ad::class)
            ->findOneBy(['title' => $title]);
    }

    private function adCreationInput(): string
    {
        return <<<JSON
{
    "title": "Smoke test case",
    "content": "Content",
    "type": "job"
}
JSON;
    }

    private function automobileAdCreationInput(string $search): string
    {
        return <<<JSON
{
    "title": "My car",
    "content": "Super car",
    "type": "automobile",
    "model": "$search" 
}
JSON;
    }

    private function missingRequiredAdCreationInput(): string
    {
        return <<<JSON
{
    "content": "Content",
    "type": "job"
}
JSON;
    }

    private function adUpdateInput(): string
    {
        return <<<JSON
{
    "title": "Update title"
}
JSON;
    }

    private function json404(): string
    {
        return <<<JSON
{
    "message": "Resource not founded"
}
JSON;
    }

    private function missingMandatoriesAd400(): string
    {
        return <<<JSON
{
    "message": "Missing some mandatories"
}
JSON;
    }

    private function jsonToObject(string $json): object
    {
        return (object) json_decode($json);
    }
}
