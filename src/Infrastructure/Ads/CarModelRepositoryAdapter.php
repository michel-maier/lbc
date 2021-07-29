<?php

namespace App\Infrastructure\Ads;

use App\Core\Ads\Domain\Ad;
use App\Core\Ads\Domain\CarModel;
use App\Core\Ads\Domain\CarModelId;
use App\Core\Ads\Infrastructure\CarModelRepositoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use App\Infrastructure as Infrastructure;

class CarModelRepositoryAdapter implements CarModelRepositoryInterface
{
    protected EntityRepository $repository;
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(CarModel::class);
        $this->entityManager = $entityManager;
    }
    public function get(CarModelId $id): CarModel
    {
        $ad = $this->repository->findOneBy(['id' => $id]);

        if (null === $ad) {
            throw new Infrastructure\NotFoundException();
        }

        return $ad;
    }

    public function add(CarModel $carModel): CarModel
    {
        return $this->save($carModel);
    }

    public function save(Ad $carModel): CarModel
    {
        try {
            $this->entityManager->persist($carModel);
            $this->entityManager->flush();

            return $carModel;
        } catch (UniqueConstraintViolationException $e) {
            throw new Infrastructure\UniqueConstraintViolationException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function remove(CarModelId $id): void
    {
        $this->entityManager->remove($this->get($id));
    }

    public function findAll(): array
    {
       $this->repository->findAll();
    }
}
