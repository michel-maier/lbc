<?php

namespace App\Tests\Functional\Ads;

use App\Tests\Functional\FunctionalToolTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;;

class CreateTest extends WebTestCase
{
    use FunctionalToolTrait;

    public function testIShouldCreateAd(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/ads', [],  [], [], $this->adCreationInput());
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $object = $this->jsonToObject($response->getContent());
        $this->assertEquals('Smoke test case', $object->title);
        $this->assertEquals('Content', $object->content);
        $this->assertEquals('job', $object->type);
    }

    /**
     * @dataProvider provideAutomobileAdsAccordingTests
     */
    public function testIShouldCreateAutomobileAd(string $search, string $expectedManufacturer, string $expectedModel): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/ads', [],  [], [], $this->automobileAdCreationInput($search));
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $object = $this->jsonToObject($response->getContent());
        $this->assertEquals('My car', $object->title);
        $this->assertEquals('Super car', $object->content);
        $this->assertEquals('automobile', $object->type);
        $this->assertEquals($expectedModel, $object->model);
        $this->assertEquals($expectedManufacturer, $object->manufacturer);

    }

    public function provideAutomobileAdsAccordingTests(): array
    {
        return [
            ['rs4 avant', 'Audi', 'Rs4'],
            ['gran turismo serie 5', 'BMW', 'Serie 5'],
            ['ds 3 crossback', 'Citroen', 'Ds3'],
            ['C4 Picasso', 'Citroen', 'C4 Picasso']

        ];
    }

    public function testIShouldGet400StatusCodeResponseOnMalformedJsonBody(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/ads', [],  [], [], '{"malformed');
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testIShouldGet400StatusCodeResponseWithMissingData(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/ads', [],  [], [], $this->missingRequiredAdCreationInput());
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString($this->missingMandatoriesAd400(), $response->getContent());
    }
}