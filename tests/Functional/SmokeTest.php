<?php

namespace App\Tests\Functional;

use Iterator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    use AdToolTrait;

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful(string $method, Callable $uri, string $content = null): void
    {
        $client = self::createClient();
        $client->request($method, $uri(), [], [], [], $content);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider(): iterator
    {
        yield '[0] - [GET] /api/ads/{id}' => ['GET', fn() =>  sprintf('/api/ads/%s', $this->findOneAdByTitle('Php Developer')->getId())];
        yield '[1] - [GET] /api/ads' => ['GET', fn() => '/api/ads'];
        yield '[2] - [POST] /api/ads' => ['POST', fn() => '/api/ads', $this->adCreationInput()];
        yield '[3] - [PATCH] /api/ads/{id}' => ['PATCH', fn() => sprintf('/api/ads/%s', $this->findOneAdByTitle('Php Developer')->getId()), $this->adUpdateInput()];
        yield '[4] - [DELETE] /api/ads/{id}' => ['DELETE', fn() => sprintf('/api/ads/%s', $this->findOneAdByTitle('Php Developer')->getId())];
    }
}