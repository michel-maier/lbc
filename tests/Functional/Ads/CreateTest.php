<?php

namespace App\Tests\Functional\Ads;

use App\Tests\Functional\AdToolTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;;

class CreateTest extends WebTestCase
{
    use AdToolTrait;

    public function testIShouldCreateAd(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/ads', [],  [], [], $this->adCreationInput());
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
    }
}