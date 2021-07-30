<?php

namespace App\Tests\Functional\Ads;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FindAllTest extends WebTestCase
{
    public function testIShouldFindAllAd()
    {
        $client = static::createClient();
        $client->request('GET', '/api/ads');

        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
