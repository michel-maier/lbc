<?php

namespace App\Tests\Core\Ads\Application;

use App\Core\Ads\Application\DefaultAdResponse;
use App\Core\Ads\Application\FindAllAdsService;
use App\Core\Ads\Application\GetAdService;
use App\Core\Ads\Domain\Ad;
use App\Core\Ads\Domain\AutomobileAd;
use App\Core\Ads\Domain\JobAd;
use App\Core\Ads\Infrastructure\AdRepositoryInterface;
use App\Core\DomainException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class FindAllAdsServiceTest extends TestCase
{
    use ProphecyTrait;
    use StubCarModelsTrait;

    private $adRepository;
    private FindAllAdsService $service;

    protected function setUp(): void
    {
        $this->adRepository = $this->prophesize(AdRepositoryInterface::class);

        $this->service = new FindAllAdsService($this->adRepository->reveal());
    }

    public function testIShouldFindAllAds()
    {
        $ads = [new JobAd('title', 'content'), new AutomobileAd('title', 'content', 'Aygo', 'Toyota')];
        $this->adRepository
            ->findAll()
            ->willReturn($ads);

        $result = ($this->service)();

        $this->assertIsArray( $result);
        $this->assertCount(2, $result);
    }
}