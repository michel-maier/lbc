<?php

namespace App\Tests\Functional\Ads;

use App\Tests\Functional\FunctionalToolTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;;

class GetTest extends WebTestCase
{
    use FunctionalToolTrait;

    public function testIShouldGetAd(): void
    {
        $client = static::createClient();
        $uri = sprintf('/api/ads/%s', $this->findOneAdByTitle('Php Developer')->getId());

        $client->request('GET', $uri);

        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testIShouldGetA404ResponseOnUnknown() : void
    {
        $client = static::createClient();
        $uri = '/api/ads/123e4567-e89b-12d3-a456-426614174000';

        $client->request('GET', $uri);

        $response = $client->getResponse();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString($this->json404(), $response->getContent());
    }
}