<?php

namespace App\Tests\Core\Ads\Application;

use App\Core\Ads\Application\FindAllAdsService;
use App\Core\Ads\Domain\AutomobileAd;
use App\Core\Ads\Domain\InitializeCarModelsTrait;
use App\Core\Ads\Domain\JobAd;
use App\Core\Ads\Infrastructure\AdRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class FindAllAdsServiceTest extends TestCase
{
    use ProphecyTrait;
    use InitializeCarModelsTrait;

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

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }
}
