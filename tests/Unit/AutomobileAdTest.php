<?php

namespace App\Tests\Unit;

use App\Core\Ads\Application\NewAdRequest;
use App\Core\Ads\Domain\AutomobileAd;
use App\Core\Ads\Domain\InitializeCarModelsTrait;
use App\Core\Ads\Infrastructure\CarModelRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class AutomobileAdTest extends TestCase
{
    use ProphecyTrait;
    use InitializeCarModelsTrait;

    /**
     * @dataProvider  provideSearch
     */
    public function testSearchCarModelAlgorithm(string $search, string $manufacturer, string $model)
    {
        $carModelRepository = $this->prophesize(CarModelRepositoryInterface::class);
        $carModelRepository
           ->findAll()
           ->willReturn(iterator_to_array($this->buildCarModels()));

        $ad = AutomobileAd::createFromNewAutomobileRequest(
            new NewAdRequest('title', 'content', 'automobile', $search),
            $carModelRepository->reveal()
        );

        $this->assertEquals($model, $ad->getModel());
        $this->assertEquals($manufacturer, $ad->getManufacturer());
    }

    public function provideSearch(): array
    {
        return [
            '0 - rs4 avant' => ['rs4 avant', 'Audi', 'Rs4'],
            '1 - gran turismo serie 5' => ['gran turismo serie 5', 'BMW', 'Serie 5'],
            '2 - ds 3 crossback' => ['ds 3 crossback', 'Citroen', 'Ds3'],
            '3 - C4 Picasso' => ['c4 picasso', 'Citroen', 'C4 Picasso'],
        ];
    }
}
