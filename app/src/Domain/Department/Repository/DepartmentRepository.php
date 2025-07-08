<?php

declare(strict_types=1);

namespace App\Domain\Department\Repository;

use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\Department\Department;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Department>
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    public function add(Department $department): void
    {
        $this->getEntityManager()->persist($department);
    }

    public function getById(Uuid $id): ?Department
    {
        /**
         * @psalm-var Department|null $department
         */
        $department = $this->createQueryBuilder('d')
            ->where('d.id = :id')
            ->setParameter('id', $id, UuidType::NAME)
            ->getQuery()
            ->getOneOrNullResult();

        return $department;
    }

    public function getByIdOrFail(Uuid $id): Department
    {
        $department = $this->getById($id);

        if (null === $department) {
            throw new EntityNotFoundException();
        }

        return $department;
    }

    public function isDepartmentWithNameExists(string $name): bool
    {
        return (bool) $this->createQueryBuilder('d')
            ->select('1')
            ->where('d.name = :name')
            ->setParameter('name', $name, Types::STRING)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
