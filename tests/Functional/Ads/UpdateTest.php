<?php

namespace App\Tests\Functional\Ads;

use App\Tests\Functional\AdToolTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;;

class UpdateTest extends WebTestCase
{
    use AdToolTrait;

    public function testIShouldUpdateAd()
    {
        $client = static::createClient();
        $client->request('PATCH', sprintf('/api/ads/%s', $this->findOneAdByTitle('Php Developer')->getId()), [],  [], [], $this->adUpdateInput());

        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(200, $response->getStatusCode());
    }
}