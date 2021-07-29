<?php

namespace App\Tests\Core\Ads\Application;

use App\Core\Ads\Application\DefaultAdResponse;
use App\Core\Ads\Application\NewAdRequest;
use App\Core\Ads\Application\NewAdService;
use App\Core\Ads\Domain\Ad;
use App\Core\Ads\Domain\InitializeCarModelsTrait;
use App\Core\Ads\Infrastructure\AdRepositoryInterface;
use App\Core\Ads\Infrastructure\CarModelRepositoryInterface;
use App\Core\DomainException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Argument;

class NewAdServiceTest extends TestCase
{
    use ProphecyTrait;
    use InitializeCarModelsTrait;

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
    public function testIShouldCreateGenericAdThenGetResponse(NewAdRequest $req, DefaultAdResponse $expected): void
    {
        //Arrange
        $this->adRepository
            ->add(Argument::any())
            ->willReturnArgument(0);
        //Act
        $result = ($this->service)($req);

        //Assert
        $this->assertInstanceOf(DefaultAdResponse::class, $result);
        $this->assertNotNull($result->getId());
        $this->assertEquals($result->getTitle(), $result->getTitle());
        $this->assertEquals($result->getContent(), $result->getContent());
        $this->assertEquals($result->getType(), $result->getType());
        $this->assertNull($result->getModel());
        $this->assertNull($result->getManufacturer());
    }

    public function provideAdCreationRequest(): array
    {
        return [
            '0 - I should create JobAd' => [new NewAdRequest('job title', 'job content', Ad::JOB_TYPE), new DefaultAdResponse('id', 'job title', 'job content', Ad::JOB_TYPE)],
            '1 - I should create RealEstateAd' => [new NewAdRequest('real estate title', 'real estate content', Ad::REAL_ESTATE_TYPE), new DefaultAdResponse('id', 'real estate title', 'real estate content', Ad::REAL_ESTATE_TYPE)],
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
            ->willReturn(iterator_to_array($this->buildCarModels()));

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
            ->willReturn(iterator_to_array($this->buildCarModels()));

        $this->expectExceptionObject(new DomainException('"309" does not match any model'));

        ($this->service)($req);
    }
}