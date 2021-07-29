<?php

namespace App\Infrastructure\Ads;

use App\Core\Ads\Domain\Ad;
use App\Core\Ads\Domain\AdId;
use App\Core\Ads\Infrastructure\AdRepositoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use App\Infrastructure as Infrastructure;

class AdRepositoryAdapter implements AdRepositoryInterface
{
    protected EntityRepository $repository;
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Ad::class);
        $this->entityManager = $entityManager;
    }
    public function get(AdId $id): Ad
    {
        $ad = $this->repository->findOneBy(['id' => $id]);

        if (null === $ad) {
            throw new Infrastructure\NotFoundException();
        }

        return $ad;
    }

    public function add(Ad $ad): Ad
    {
        return $this->save($ad);
    }

    public function save(Ad $ad): Ad
    {
        try {
            $this->entityManager->persist($ad);
            $this->entityManager->flush();

            return $ad;
        } catch (UniqueConstraintViolationException $e) {
            throw new Infrastructure\UniqueConstraintViolationException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function remove(AdId $id): void
    {
        $this->entityManager->remove($this->get($id));
    }

    public function findAll(): array
    {
       $this->repository->findAll();
    }
}
