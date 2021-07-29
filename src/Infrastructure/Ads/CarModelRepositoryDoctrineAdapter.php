<?php

namespace App\Infrastructure\Ads;

use App\Core\Ads\Domain\CarModel;
use App\Core\Ads\Domain\CarModelId;
use App\Core\Ads\Infrastructure\CarModelRepositoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use App\Infrastructure\Exceptions as Infrastructure;
use Doctrine\ORM\EntityRepository;

class CarModelRepositoryDoctrineAdapter implements CarModelRepositoryInterface
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
        $carModel = $this->repository->find($id);

        if (null === $carModel) {
            throw new Infrastructure\NotFoundException();
        }

        return $carModel;
    }

    public function add(CarModel $carModel): CarModel
    {
        return $this->save($carModel);
    }

    public function save(CarModel $carModel): CarModel
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
        $this->entityManager->flush();
    }

    public function findAll(): array
    {
       return $this->repository->findAll();
    }
}
