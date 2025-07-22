<?php

declare(strict_types=1);

namespace App\Domain\Department\Repository;

use App\Domain\Common\Exception\EntityNotFound\EntityNotFoundEnum;
use App\Domain\Common\Exception\EntityNotFound\EntityNotFoundException;
use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\Department\Department;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
            throw new EntityNotFoundException(EntityNotFoundEnum::DEPARTMENT);
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

    /**
     * @psalm-param list<string> $searchWords
     *
     * @psalm-return Paginator<Department>
     */
    public function listDepartments(?array $searchWords, int $count, int $offset): Paginator
    {
        $departments = $this->createQueryBuilder('d')
            ->orderBy('d.name', 'DESC')
            ->setMaxResults($count)
            ->setFirstResult($offset);

        if (null !== $searchWords) {
            foreach ($searchWords as $index => $word) {
                $paramName = "param_{$index}";

                $departments
                    ->orWhere('LOWER(d.name) LIKE :' . $paramName)
                    ->setParameter($paramName, '%' . mb_strtolower($word) . '%', Types::STRING);
            }
        }

        $departments->andWhere('d.deletedAt IS NULL');

        /**
         * @psalm-var Paginator<Department> $paginator
         */
        $paginator = new Paginator($departments->getQuery());

        return $paginator;
    }
}
