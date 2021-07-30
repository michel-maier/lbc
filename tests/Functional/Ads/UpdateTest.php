<?php

namespace App\Tests\Functional\Ads;

use App\Tests\Functional\FunctionalToolTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateTest extends WebTestCase
{
    use FunctionalToolTrait;

    public function testIShouldUpdateAd()
    {
        $client = static::createClient();
        $client->request('PATCH', sprintf('/api/ads/%s', $this->findOneAdByTitle('Php Developer')->getId()), [], [], [], $this->adUpdateInput());

        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testIShouldGetA404ResponseOnUnknown(): void
    {
        $client = static::createClient();
        $client->request('PATCH', '/api/ads/123e4567-e89b-12d3-a456-426614174000', [], [], [], $this->adUpdateInput());

        $response = $client->getResponse();
        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(404, $response->getStatusCode());
    }
}
