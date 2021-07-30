<?php

namespace App\Tests\Functional\Ads;

use App\Tests\Functional\FunctionalToolTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteTest extends WebTestCase
{
    use FunctionalToolTrait;

    public function testIShouldDeleteAd(): void
    {
        $client = static::createClient();
        $uri = sprintf('/api/ads/%s', $this->findOneAdByTitle('Php Developer')->getId());

        $client->request('DELETE', $uri);
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(204, $response->getStatusCode());
    }
}
