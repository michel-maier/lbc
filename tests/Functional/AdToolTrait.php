<?php

namespace App\Tests\Functional;

use App\Core\Ads\Domain\Ad;

trait AdToolTrait
{
    private function findOneAdByTitle(string $title): Ad
    {
        return self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository(Ad::class)
            ->findOneBy(['title' => $title]);
    }

    private function adCreationInput()
    {
        return  <<<JSON
{
    "title": "Smoke test case",
    "content": "Content",
    "type": "job"
}
JSON;
    }

    private function adUpdateInput()
    {
        return  <<<JSON
{
    "title": "Update title"
}
JSON;
    }
}