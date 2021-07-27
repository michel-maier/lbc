<?php

namespace App\Tests\Core\Ads\Application;

use App\Core\Ads\Application\NewAdRequest;
use App\Core\Ads\Application\NewAdResponse;
use App\Core\Ads\Application\NewAdService;
use App\Core\Ads\Domain\Ad;
use App\Core\Ads\Domain\CarModel;
use App\Core\Ads\Infrastructure\AdRepositoryInterface;
use App\Core\Ads\Infrastructure\CarModelRepositoryInterface;
use App\Core\DomainException;
use Iterator;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Argument;

class AdCreationServiceTest extends TestCase
{
    use ProphecyTrait;

    private $adRepository;
    private $carModelRepository;
    private NewAdService $service;

    protected function setUp(): void
    {
        $this->adRepository = $this->prophesize(AdRepositoryInterface::class);
        $this->carModelRepository = $this->prophesize(CarModelRepositoryInterface::class);

        $this->service = new NewAdService($this->adRepository->reveal(), $this->carModelRepository->reveal());
    }

    /**
     * @dataProvider provideAdCreationRequest
     */
    public function testIShouldCreateGenericAdThenGetResponse(NewAdRequest $req, NewAdResponse $expected): void
    {
        //Arrange
        $this->adRepository
            ->add(Argument::any())
            ->willReturnArgument(0);
        //Act
        $result = ($this->service)($req);

        //Assert
        $this->assertInstanceOf(NewAdResponse::class, $result);
        $this->assertNotNull($expected->getId());
        $this->assertEquals($req->getTitle(), $expected->getTitle());
        $this->assertEquals($req->getContent(), $expected->getContent());
        $this->assertEquals($req->getType(), $expected->getType());
        $this->assertEquals(null, $expected->getModel());
        $this->assertEquals(null, $expected->getManufacturer());
    }

    public function provideAdCreationRequest(): array
    {
        return [
            '0 - I should create JobAd' => [new NewAdRequest('job title', 'job content', Ad::JOB_TYPE), new NewAdResponse('id', 'job title', 'job content', Ad::JOB_TYPE)],
            '1 - I should create RealEstateAd' => [new NewAdRequest('real estate title', 'real estate content', Ad::REAL_ESTATE_TYPE), new NewAdResponse('id', 'real estate title', 'real estate content', Ad::REAL_ESTATE_TYPE)],
        ];
    }
    /**
     * @dataProvider provideAdCreationRequestForAutomobile
     */
    public function testIShouldCreateAutomobileAdThenDeduceModelAndManufacturer(string $search, $manufacturer, $model): void
    {
        $req = new NewAdRequest('title', 'content', Ad::AUTOMOBILE_TYPE, $search);
        $this->adRepository
            ->add(Argument::any())
            ->willReturnArgument(0);
        $this->carModelRepository
            ->findAll()
            ->willReturn(iterator_to_array($this->stubCarModels()));

        $result = ($this->service)($req);

        $this->assertEquals($result->getModel(), $model);
        $this->assertEquals($result->getManufacturer(), $manufacturer);
    }

    public function provideAdCreationRequestForAutomobile(): array
    {
        return [
            '0 - rs4 avant' => ['rs4 avant', 'Audi', 'Rs4'],
            '1 - gran turismo serie 5' => ['gran turismo serie 5', 'BMW', 'Serie 5'],
            '2 - ds 3 crossback' => ['ds 3 crossback', 'Citroen', 'Ds3'],
        ];
    }

    public function testIShouldGetAnExceptionWithABadModelSearch()
    {
        $req = new NewAdRequest('title', 'content', Ad::AUTOMOBILE_TYPE, '309');
        $this->adRepository
            ->add(Argument::any())
            ->willReturnArgument(0);
        $this->carModelRepository
            ->findAll()
            ->willReturn(iterator_to_array($this->stubCarModels()));

        $this->expectExceptionObject(new DomainException('"309" does not match any model'));

        ($this->service)($req);
    }

    private function stubCarModels(): Iterator
    {
        foreach(['Cabriolet', 'Q2', 'Q3', 'Q5', 'Q7', 'Q8', 'R8', 'Rs3', 'Rs4', 'Rs5', 'Rs7', 'S3', 'S4', 'S4 Avant', 'S4 Cabriolet', 'S5', 'S7', 'S8', 'SQ5', 'SQ7', 'Tt', 'Tts', 'V8'] as $model)
        {
            yield new CarModel($model, 'Audi');
        }
        foreach(['M3', 'M4', 'M5', 'M535', 'M6', 'M635', 'Serie 1', 'Serie 2', 'Serie 3', 'Serie 4', 'Serie 5', 'Serie 6', 'Serie 7', 'Serie 8'] as $model)
        {
            yield new CarModel($model, 'BMW');
        }
        foreach(['C1', 'C15', 'C2', 'C25', 'C25D', 'C25E', 'C25TD', 'C3', 'C3 Aircross', 'C3 Picasso', 'C4', 'C4 Picasso', 'C5', 'C6', 'C8', 'Ds3', 'Ds4', 'Ds5'] as $model)
        {
            yield new CarModel($model, 'Citroen');
        }
    }
}