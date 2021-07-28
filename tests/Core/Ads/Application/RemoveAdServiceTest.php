<?php

namespace App\Tests\Core\Ads\Application;

use App\Core\Ads\Application\RemoveAdResponse;
use App\Core\Ads\Application\RemoveAdService;
use App\Core\Ads\Infrastructure\AdRepositoryInterface;
use App\Core\DomainException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class RemoveAdServiceTest extends TestCase
{
    use ProphecyTrait;
    use StubCarModelsTrait;

    private $adRepository;
    private RemoveAdService $service;

    protected function setUp(): void
    {
        $this->adRepository = $this->prophesize(AdRepositoryInterface::class);

        $this->service = new RemoveAdService($this->adRepository->reveal());
    }

    public function testIShouldDeleteAdAndINoteAnRemoveAdResponseInReturn()
    {
        $this->adRepository
            ->remove(Argument::any());

        $uuid = '123e4567-e89b-12d3-a456-426614174000';
        $result = ($this->service)($uuid);

        $this->assertInstanceOf(RemoveAdResponse::class, $result);
        $this->assertEquals($uuid, $result->getUuid());
    }

    public function testIShouldGetAnErrorOnTryingToDeleteAnAddWithAnMalformedUuid()
    {
        $this->adRepository
            ->remove(Argument::any());
        $uuid = '1234';
        $this->expectExceptionObject(new DomainException('"1234" is not a valid uuid'));

       ($this->service)($uuid);
    }
}