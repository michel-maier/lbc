<?php

namespace App\Tests\Core\Ads\Application;

use App\Core\Ads\Application\DefaultAdResponse;
use App\Core\Ads\Application\GetAdService;
use App\Core\Ads\Domain\InitializeCarModelsTrait;
use App\Core\Ads\Domain\JobAd;
use App\Core\Ads\Infrastructure\AdRepositoryInterface;
use App\Core\DomainException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class GetAdServiceTest extends TestCase
{
    use ProphecyTrait;
    use InitializeCarModelsTrait;

    private $adRepository;
    private GetAdService $service;

    protected function setUp(): void
    {
        $this->adRepository = $this->prophesize(AdRepositoryInterface::class);

        $this->service = new GetAdService($this->adRepository->reveal());
    }

    public function testIShouldGetAd()
    {
        $ad = new JobAd('title', 'content');
        $this->adRepository
            ->get(Argument::any())
            ->willReturn($ad);

        $result = ($this->service)('123e4567-e89b-12d3-a456-426614174000');

        $this->assertInstanceOf(DefaultAdResponse::class, $result);
        $this->assertEquals($ad->getTitle(), $result->getTitle());
        $this->assertEquals($ad->getContent(), $result->getContent());
        $this->assertEquals($ad->getType(), $result->getType());
    }

    public function testIShouldGetAnErrorOnTryingToGetAnAddWithAnMalformedUuid()
    {
        $this->adRepository
            ->get(Argument::any());
        $uuid = '1234';
        $this->expectExceptionObject(new DomainException('"1234" is not a valid uuid'));

        ($this->service)($uuid);
    }
}
