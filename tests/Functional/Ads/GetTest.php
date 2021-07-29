<?php

namespace App\Tests\Functional\Ads;

use App\Tests\Functional\AdToolTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;;

class GetTest extends WebTestCase
{
    use AdToolTrait;

    public function testIShouldGetAd()
    {
        $client = static::createClient();
        $uri = sprintf('/api/ads/%s', $this->findOneAdByTitle('Php Developer')->getId());

        $client->request('GET', $uri);

        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(200, $response->getStatusCode());
    }
}