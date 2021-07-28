<?php

namespace App\Tests\Core\Ads\Application;

use App\Core\Ads\Application\DefaultAdResponse;
use App\Core\Ads\Application\UpdateAdRequest;
use App\Core\Ads\Application\UpdateAdService;
use App\Core\Ads\Domain\Ad;
use App\Core\Ads\Domain\AutomobileAd;
use App\Core\Ads\Domain\JobAd;
use App\Core\Ads\Domain\RealEstateAd;
use App\Core\Ads\Infrastructure\AdRepositoryInterface;
use App\Core\Ads\Infrastructure\CarModelRepositoryInterface;
use App\Core\DomainException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Argument;

class UpdateAdServiceTest extends TestCase
{
    use ProphecyTrait;
    use StubCarModelsTrait;

    private $adRepository;
    private $carModelRepository;
    private UpdateAdService $service;
    private StubCarModelsTrait $stubCarModelsTrait;

    protected function setUp(): void
    {
        $this->adRepository = $this->prophesize(AdRepositoryInterface::class);
        $this->carModelRepository = $this->prophesize(CarModelRepositoryInterface::class);

        $this->service = new UpdateAdService($this->adRepository->reveal(), $this->carModelRepository->reveal());
    }

    /**
     * @dataProvider provideUpdateAdRequest
     */
    public function testIShouldUpdateGenericAdThenGetResponse(UpdateAdRequest $req, Ad $toUpdate, DefaultAdResponse $expected): void
    {
        //Arrange
        $this->adRepository
            ->get(Argument::any())
            ->willReturn($toUpdate);
        $this->adRepository
            ->save(Argument::any())
            ->willReturnArgument(0);
        //Act
        $result = ($this->service)($req);

        //Assert
        $this->assertInstanceOf(DefaultAdResponse::class, $result);
        $this->assertNotNull($expected->getId());
        $this->assertEquals($expected->getTitle(), $result->getTitle(),);
        $this->assertEquals($expected->getContent(), $result->getContent(), );
        $this->assertNull($result->getModel());
        $this->assertNull($result->getManufacturer());
    }

    public function provideUpdateAdRequest(): array
    {
        return [
            '0 - I should update JobAd' => [
                new UpdateAdRequest('123e4567-e89b-12d3-a456-426614174000', 'new job title', 'new job content'),
                new JobAd('to change', 'to change'),
                new DefaultAdResponse('123e4567-e89b-12d3-a456-426614174000', 'new job title', 'new job content', Ad::JOB_TYPE)
            ],
            '1 - I should update RealEstateAd' => [
                new UpdateAdRequest('123e4567-e89b-12d3-a456-426614174000', 'new real estate title', 'new real estate content'),
                new RealEstateAd('to change', 'to change'),
                new DefaultAdResponse('123e4567-e89b-12d3-a456-426614174000', 'new real estate title', 'new real estate content', Ad::REAL_ESTATE_TYPE)
            ],
        ];
    }

    /**
     * @dataProvider providePartiallyUpdateAdRequest
     */
    public function testIShouldPartiallyUpdateGenericAdThenGetResponse(UpdateAdRequest $req, Ad $toUpdate, DefaultAdResponse $expected): void
    {
        //Arrange
        $this->adRepository
            ->get(Argument::any())
            ->willReturn($toUpdate);
        $this->adRepository
            ->save(Argument::any())
            ->willReturnArgument(0);
        //Act
        $result = ($this->service)($req);

        //Assert

        $this->assertEquals($expected->getTitle(), $result->getTitle());
        $this->assertEquals($expected->getContent(), $result->getContent());
    }

    public function providePartiallyUpdateAdRequest(): array
    {
        return [
            '0 - I should partially update JobAd' => [
                new UpdateAdRequest('123e4567-e89b-12d3-a456-426614174000', 'new job title'),
                new JobAd('initial', 'initial'),
                new DefaultAdResponse('123e4567-e89b-12d3-a456-426614174000', 'new job title', 'initial', Ad::JOB_TYPE),
            ],
            '1 - I should partially update RealEstateAd' => [
                new UpdateAdRequest('123e4567-e89b-12d3-a456-426614174000', 'new real estate title'),
                new RealEstateAd('initial', 'initial'),
                new DefaultAdResponse('123e4567-e89b-12d3-a456-426614174000', 'new real estate title', 'initial', Ad::REAL_ESTATE_TYPE),
            ],
            '1 - I should partially update AutomobileAd' => [
                new UpdateAdRequest('123e4567-e89b-12d3-a456-426614174000', 'new automobile title'),
                new RealEstateAd('initial', 'initial'),
                new DefaultAdResponse('123e4567-e89b-12d3-a456-426614174000', 'new automobile title', 'initial', Ad::AUTOMOBILE_TYPE),
            ],
        ];
    }

    /**
     * @dataProvider provideUpdateRequestForAutomobile
     */
    public function testIShouldCreateAutomobileAdThenDeduceModelAndManufacturer(string $search, $manufacturer, $model): void
    {
        $req = new UpdateAdRequest('123e4567-e89b-12d3-a456-426614174000', 'content', Ad::AUTOMOBILE_TYPE, $search);
        $this->adRepository
            ->get(Argument::any())
            ->willReturn(new AutomobileAd('title', 'content', 'Model', 'Manufacturer'));
        $this->adRepository
            ->save(Argument::any())
            ->willReturnArgument(0);
        $this->carModelRepository
            ->findAll()
            ->willReturn(iterator_to_array($this->stubCarModels()));

        $result = ($this->service)($req);

        $this->assertEquals($result->getModel(), $model);
        $this->assertEquals($result->getManufacturer(), $manufacturer);
    }

    public function provideUpdateRequestForAutomobile(): array
    {
        return [
            '0 - rs4 avant' => ['rs4 avant', 'Audi', 'Rs4'],
            '1 - gran turismo serie 5' => ['gran turismo serie 5', 'BMW', 'Serie 5'],
            '2 - ds 3 crossback' => ['ds 3 crossback', 'Citroen', 'Ds3'],
        ];
    }

    public function testIShouldGetAnExceptionWithABadModelSearch()
    {
        $req = new UpdateAdRequest('123e4567-e89b-12d3-a456-426614174000', 'content', Ad::AUTOMOBILE_TYPE, '309');
        $this->adRepository
            ->get(Argument::any())
            ->willReturn(new AutomobileAd('title', 'content', 'Model', 'Manufacturer'));
        $this->adRepository
            ->save(Argument::any())
            ->willReturnArgument(0);
        $this->carModelRepository
            ->findAll()
            ->willReturn(iterator_to_array($this->stubCarModels()));

        $this->expectExceptionObject(new DomainException('"309" does not match any model'));

        ($this->service)($req);
    }
}